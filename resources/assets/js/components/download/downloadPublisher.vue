<template>
    <download-excel :data = "publisher_data" :before-generate = "startDownload" :before-finish = "finishDownload" name = "publisher.xls" :fields = "publisher_fields" title = "Danh sách cơ quan công bố">
        <span class="gb-icon-file-download"></span>
        <span>{{$t('nbds_lang.download')}}</span>
        <svg v-if="load" class="circular mini" viewBox="25 25 50 50" style="width: 20px">
            <circle class="path" cx="50" cy="50" r="20" style="stroke: rgba(0, 0, 0, .8)" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>
        </svg>
    </download-excel>
</template>

<script>
    import * as env from "../../env.js";
    export default {
        data: function () {
            return {
                publisher_data: [],
                publisher_fields: {
                    'Tên': 'name',
                    'Tên tiếng việt': 'name_vietnamese'
                },
            }
        },

        created() {
            this.getPublisher();
        },
        methods: {
            getPublisher() {
                axios
                    .get(env.endpointhttp + 'publishers')
                    .then(response => {
                        this.publisher_data = response.data.result.data
                    })
                    .catch(error => {
                    })
                    .finally();
            },
        }
    }
</script>

