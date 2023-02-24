<template>
    <div class="white-box  col-md-4 col-lg-4">
        <components-notifications :typeInput="typeNotification" :textInput="textNotification">
        </components-notifications>
        <div v-if="loading">
            <components-loading-page></components-loading-page>
        </div>
        <h3>Công khai dữ liệu</h3>
        <p>Bấm nút "Công khai" dưới đây để công khai tất cả dữ liệu mới nhất được thêm mới, chỉnh sửa trong hệ thống</p>
        <p style="color:red">Lưu ý: Trong khi hệ thống chạy, việc tìm kiếm sẽ xảy ra sai sót hoặc không thể sử dùng chức năng tìm kiếm</p>
        <div class="card-body" style="float:right">
            <a type="button"  @click="publicdata()" class="btn btn-info btn-rounded m-t-10 float-right">Công khai</a>
        </div>
    </div>
</template>

<script>
    import * as env from '../../../../env.js'

    export default {
        data: function () {
            return {
                typeNotification: null,
                textNotification: null,
                loading: false,
            }
        },
        mounted() {
        },
        methods: {
            publicdata() {
                this.loading = true;
                axios.get(env.endpointhttp + "admin/public")
                    .then(response => {
                        this.typeNotification = 2;
                        this.textNotification = "Cập nhật thành công.";
                    })
                    .catch(error => {
                        this.typeNotification = 1;
                        this.textNotification = "Cập nhật không thành công.";
                    })
                    .finally(() => {this.loading = false; this.typeNotification = null; this.textNotification = null;});;
            },
        }
    }
</script>

