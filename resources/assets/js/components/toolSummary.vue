<template>
    <div class="site-wrapper">
        <main class="main">
            <div class="viewContentWrapper">
                <div class="site-content rtl-supported rtl-bootstrap">
                    <div class="site-content__page">
                        <div class="publisherKey light-background">
                            <div class="wrapper-horizontal-stripes occurrenceKey page--data">
                                <section class="horizontal-stripe article-header white-background">
                                    <div class="container--desktop">
                                        <div class="row">
                                            <div class="col-xs-12 text-center">
                                                <nav class="article-header__category">
                                                    <span>Tool</span>
                                                    <span
                                                        class="article-header__category__lower">NBDS</span>
                                                </nav>
                                                <h1 class="text-center">
                                                    <p>{{$t("tool.api_summary")}}</p>
                                                </h1>
                                                <div class="article-header__intro">
                                                    <p>{{this.domain}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <section class="horizontal-stripe--paddingless white-background dataset-key__visual-summary">
                                    <div class="container">
                                        <div class="row">
                                            <nav class="tabs">
                                                <ul>
                                                    <li class="tab isActive">
                                                        <a href="/tool/summary">{{$t("tool.summary")}}</a>
                                                    </li>
                                                    <li class="tab ">
                                                        <a href="/tool/category">{{$t("tool.category")}}</a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </section>                                
                                <section class="white-background">
                                    <div class="submenu-wrapper horizontal-stripe white-background" id="submenuWrapper">
                                        <div layout="row" class="layout-row">
                                            <!--<div class="col-md-2">
                                                <span class="relative-anchor-offset"><a name="menu"></a></span>
                                                    <nav class="submenu developer-submenu" id="submenu">
                                                        <ul class="nav-parts">
                                                            <li class="part-name">
                                                                <a href="" class="h3">{{$t("tool.content")}}</a>
                                                                <ul class="nav-chapters">
                                                                    <li class="nav-chapter" du-scrollspy="common">
                                                                        <a href="#common">{{$t("tool.common_operations")}}</a>
                                                                    </li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </nav>
                                            </div>-->
                                            <div class="col-md-10">
                                                <section id="common" class="white-background page--developer__anchor">
                                                    <div class="container--narrow">
                                                        <h3>{{$t("tool.common_operations")}}</h3>
                                                        <p>{{$t("tool.common_param")}}</p>
                                                        <h3>{{$t("tool.paging")}}</h3>
                                                        <p>{{$t("tool.support_paging")}}</p>
                                                        <table class="table table-bordered table-striped table-params">
                                                            <thead>
                                                            <tr>
                                                                <th width="25%" class="total">{{$t("tool.parameter")}}</th>
                                                                <th width="75%">{{$t("tool.details")}}</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td>per_page</td>
                                                                <td>{{$t("tool.detail_per_page")}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>page</td>
                                                                <td>{{$t("tool.detail_page")}}
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
    </div>
</template>

<script>
export default {
     data: function() {
        return {          
            domain: null
        }
    },

    created() {        
        this.domain = window.location.hostname;
    }
}
</script>
