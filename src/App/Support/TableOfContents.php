<?php

namespace  Apachish\Blog\App\Support;

class TableOfContents
{
    /**
     * Parses the post's HTML content, finds h2/h3 headings, auto-generates a slug id
     * for each one, injects that id into the actual HTML (wrapping bilingual .fa/.en
     * pairs in a shared <div id="..."> so the anchor works regardless of which
     * language is currently visible via CSS), and returns both:
     *
     *   ['html' => <mutated HTML to render>, 'toc' => [['id','fa','en','level'], ...]]
     *
     * IMPORTANT: always render $result['html'], not the raw $post->content — the ids
     * only exist in the mutated version. No manual markup required from content
     * authors; it works on plain <h2 class="fa">/<h2 class="en"> pairs, or a single
     * non-bilingual <h2>/<h3>, with no special wrapper needed in the source content.
     */
    public static function process(string $html): array
    {
        if (trim($html) === '') {
            return ['html' => $html, 'toc' => []];
        }

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?><div id="__root__">' . $html . '</div>');
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);
        $root = $dom->getElementById('__root__');
        $headings = $xpath->query('.//h2 | .//h3', $root);

        $toc = [];
        $usedIds = [];
        $processed = [];

        foreach ($headings as $heading) {
            /** @var \DOMElement $heading */
            if (in_array($heading, $processed, true)) {
                continue;
            }

            $class = $heading->getAttribute('class');
            $isFa = str_contains($class, 'fa');
            $isEn = str_contains($class, 'en');
            $level = (int) substr($heading->tagName, 1); // h2 -> 2, h3 -> 3

            $faText = $isFa ? trim($heading->textContent) : '';
            $enText = $isEn ? trim($heading->textContent) : '';

            $pairNode = null;
            if ($isFa || $isEn) {
                // Look at the immediate next sibling element for the matching pair
                $sibling = $heading->nextSibling;
                while ($sibling && $sibling->nodeType !== XML_ELEMENT_NODE) {
                    $sibling = $sibling->nextSibling;
                }
                if ($sibling instanceof \DOMElement && $sibling->tagName === $heading->tagName) {
                    $siblingClass = $sibling->getAttribute('class');
                    if ($isFa && str_contains($siblingClass, 'en')) {
                        $enText = trim($sibling->textContent);
                        $pairNode = $sibling;
                    } elseif ($isEn && str_contains($siblingClass, 'fa')) {
                        $faText = trim($sibling->textContent);
                        $pairNode = $sibling;
                    }
                }
            }

            $baseText = $faText ?: $enText;
            $id = self::slugify($baseText);
            if ($id === '') {
                $id = 'section';
            }
            if (isset($usedIds[$id])) {
                $usedIds[$id]++;
                $id .= '-' . $usedIds[$id];
            } else {
                $usedIds[$id] = 0;
            }

            $toc[] = ['id' => $id, 'fa' => $faText, 'en' => $enText, 'level' => $level];

            // Inject the id directly. If there's a bilingual pair, wrap both in a
            // shared <div id="..."> since only one of the two is visible at a time
            // (the other has display:none) and an anchor can't jump to a hidden node.
            if ($pairNode) {
                $wrapper = $dom->createElement('div');
                $wrapper->setAttribute('id', $id);
                $wrapper->setAttribute('class', 'section-anchor');
                $heading->parentNode->insertBefore($wrapper, $heading);
                $wrapper->appendChild($heading);
                $wrapper->appendChild($pairNode);
                $processed[] = $pairNode;
            } else {
                $heading->setAttribute('id', $id);
            }
        }

        // Serialize back to HTML, stripping the temporary wrapper div we added for parsing
        $innerHtml = '';
        foreach (iterator_to_array($root->childNodes) as $child) {
            $innerHtml .= $dom->saveHTML($child);
        }

        return ['html' => $innerHtml, 'toc' => $toc];
    }

    protected static function slugify(string $text): string
    {
        // Keep Persian/Arabic letters and latin letters/numbers, replace everything else with a dash
        $text = trim($text);
        $text = preg_replace('/[\s]+/u', '-', $text);
        $text = preg_replace('/[^\p{L}\p{N}\-]+/u', '', $text);
        $text = preg_replace('/-+/', '-', $text);
        return trim($text, '-');
    }
}
