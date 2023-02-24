<template>
    <div class="site-wrapper pt-5">
        <main class="main">
            <div class="viewContentWrapper">
                <div class="site-content">
                    <div class="site-content__page">
                        <div class="datasetKey2 light-background">
                            <div class="wrapper-horizontal-stripes">
                                <div class="pt-5 article-header white-background">
                                    <div class="container--desktop">
                                        <div class="row">
                                            <header class="col-xs-12 text-center" style="color:#4e565f">
                                                <nav class="article-header__category article-header__category--deep"
                                                    style="text-align: center !important">
                                                    <span class="article-header__category__upper" style="width: unset">
                                                        <a href="#">ÁP LỰC LÊN ĐA DẠNG SINH HỌC</a>
                                                    </span>
                                                </nav>
                                                <h1 class="text-center">
                                                    <span>{{ value.chi_thi.ten }}</span>
                                                </h1>
                                                <ol>
                                                    <li>
                                                        <p class="inherit">
                                                            {{ value.phan_cap.name }}
                                                        </p>
                                                    </li>
                                                </ol>
                                            </header>
                                        </div>
                                    </div>
                                </div>
                                <section class="horizontal-stripe article-header white-background" style="border-: 1px solid #e7e0e0">
                                    <div class="container--desktop">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="container-fluid card rtl-bootstrap">
                                                </div>
                                                <section class="term-block">
                                                    <div class="term-block__header">
                                                        <h3>
                                                            {{ $t("nbds_lang.table_category_info") }}
                                                        </h3>
                                                    </div>
                                                    <div class="term-block__terms">
                                                        <table class="table table--compact">
                                                            <colgroup>
                                                                <col span="1" style="width: 30%" />
                                                                <col span="1" style="width: 70%" />
                                                            </colgroup>
                                                            <tbody>
                                                                <tr>
                                                                    <td dir="auto">Tên chỉ thị</td>
                                                                    <td dir="auto">{{ value.chi_thi.ten }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td dir="auto">Phân cấp</td>
                                                                    <td dir="auto">{{ value.phan_cap.name }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td dir="auto">Thời gian bắt đầu</td>
                                                                    <td dir="auto">{{ value.bat_dau }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td dir="auto">Thời gian kết thúc</td>
                                                                    <td dir="auto">{{ value.ket_thuc }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td dir="auto">Đơn vị báo cáo</td>
                                                                    <td dir="auto">{{ value.don_vi_bao_cao}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td dir="auto">Kỳ báo cáo</td>
                                                                    <td dir="auto">{{ value.ky_bao_cao }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td dir="auto">Giá trị</td>
                                                                    <td dir="auto">{{ value.gia_tri ?
                                                                            value.gia_tri + " (" +
                                                                            value.don_vi_tinh + ")" : ""
                                                                    }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td dir="auto">Mô tả</td>
                                                                    <td dir="auto">{{ value.mo_ta }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td dir="auto">Nguồn dữ liệu</td>
                                                                    <td dir="auto">{{ value.nguon_du_lieu }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td dir="auto">Đính kèm</td>
                                                                    <td>
                                                                        <a :href="'/' + item.disk"
                                                                            v-for="item in value.fileList"
                                                                            :key="item.id">
                                                                            <div>{{ item.name }}</div>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer-component></footer-component>
    </div>
</template>
  
<script>
// import Header from "./header-detail.vue";
export default {
    props: [
        "value",
    ],
    data: function () {
        return {
        };
    },
    created() {
        console.log(this.value)
    },
    methods: {

    },
};
</script>
<style scoped>
.site-content__page {
    min-height: calc(100vh - 18.542857142848571428571428571429rem);
}
</style>
  