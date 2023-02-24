<template>
    <download-excel :data = "this.value" :before-generate = "startDownload" :before-finish = "finishDownload" name = "taxon.xls" :fields = "taxon_fields" title = "Danh sách phân loại học/danh mục loài">
        <span class="gb-icon-file-download"></span>
        <span>{{$t('nbds_lang.download')}}</span>
        <svg v-if="load" class="circular mini load_download" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" style="stroke: rgba(0, 0, 0, .8)" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>
        </svg>
    </download-excel>
</template>

<script>
    import * as env from "../../env.js";
    export default {
        props: ['value'],
        data: function () {
            return {
                load: false,
                taxon_fields: {
                    'Tên khoa học': 'scientific_name',
                    'Giới': 'king_dom.name',
                    'Ngành': 'phylum.name',
                    'Lớp': 'class.name',
                    'Bộ': 'order.name',
                    'Họ': 'family.name',
                    'Chi': 'genus.name',
                    'Loài': 'species.name',
                    'Bộ dữ liệu': 'data_resource.title'
                },
            }
        },
        methods: {
            startDownload(){
                this.load = true;
            },
            finishDownload(){
                this.load = false;
            }
        }
    }
</script>

