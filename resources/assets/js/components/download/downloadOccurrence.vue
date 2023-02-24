<template>
    <download-excel :data = "this.value" :before-generate = "startDownload" :before-finish = "finishDownload" name = "occurrence.xls" :fields = "occurrence_fields" title = "Danh sách phân bố">
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
                occurrence_fields: {
                    'Tên khoa học': 'scientific_name',
                    'Kinh độ': 'decimal_longitude',
                    'Vĩ độ': 'decimal_latitude',
                    'Ngày diễn ra sự kiện': 'event_date',
                    'Bộ dữ liệu': 'data_resource.title',
                    'Hạng phân loại': 'darwin_core_taxon.taxon_rank',
                    'Giới': 'darwin_core_taxon.king_dom.name',
                    'Ngành': 'darwin_core_taxon.phylum.name',
                    'Lớp': 'darwin_core_taxon.class.name',
                    'Bộ': 'darwin_core_taxon.order.name',
                    'Họ': 'darwin_core_taxon.family.name',
                    'Chi': 'darwin_core_taxon.genus.name',
                    'Loài': 'darwin_core_taxon.species.name',
                    'Loài con': 'darwin_core_taxon.sub_species.name',
                    'Tài liệu tham khảo': 'references',
                    'Cơ sở của bản ghi': 'basis_of_record',
                    'Nhận xét về phân bố': 'occurrence_remarks',
                    'Ghi nhận bởi': 'recorded_by',
                    'Số đếm cá thể': 'individual_count',
                    'Giới tính': 'sex',
                    'Điều kiện sinh sản': 'reproductive_condition',
                    'Giai đoạn vòng đời': 'life_stage',
                    'Hành vi': 'behavior',
                    'Thủ tục thu mẫu': 'sampling_protocol',
                    'Ngày diễn ra sự kiện': 'event_date',
                    'Vị trí': 'locality',
                    'Sao y ngày diễn ra sự kiện': 'verbatim_event_date',
                    'Độ cao tối đa (m)': 'maximum_elevation_in_meters',
                    'Sao y độ sâu': 'verbatim_depth',
                    'Tính ngữ riêng': 'specific_epithet',
                    'Tác giả tên khoa học': 'scientific_name_authorship',
                    'Vùng': 'region.name_vietnamese',
                    'Tỉnh': 'province.name_vietnamese',
                    'Quận': 'district.name_vietnamese',
                    'Khu bảo tồn': 'protected_area.name',
                    'Sơ đồ': 'plot.name'
                },
            }
        },
        methods: {     
            startDownload(){
                this.load = true;
            },
            finishDownload(){
                this.load = false;
            },
        }
    }
</script>

