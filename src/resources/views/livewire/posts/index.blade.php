<div class="space-y-6">

    <x-admin.common.component-card title="{{$title}}"  link_create="{{$link_create}}" >
        <x-admin.tables.basic-tables.basic-tables-two     :data="$data"     :headers="$headers" :filters="$filters" :statusLabels="$statusLabels" :classes="$classes"/>
    </x-admin.common.component-card>


</div>
