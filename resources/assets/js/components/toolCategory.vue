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
                                                    <li class="tab">
                                                        <a href="/tool/summary">{{$t("tool.summary")}}</a>
                                                    </li>
                                                    <li class="tab isActive">
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
                                            <div class="col-md-10">
                                                <section id="searching" class="white-background page--developer__anchor">
                                                    <div class="container--narrow">
                                                        <div class="page--developer__anchor" id="temporalCoverages">
                                                            <h3>{{$t("tool.searching_names")}}</h3>
                                                            <p>{{$t("tool.detail_searching_names")}}</p>
                                                            <table class="api table table-bordered table-striped table-params">
                                                                <thead>
                                                                    <tr>
                                                                        <th>{{$t("tool.resource_url")}}</th>
                                                                        <th>{{$t("tool.method")}}</th>
                                                                        <th>{{$t("tool.response")}}</th>
                                                                        <th>{{$t("tool.description")}}</th>
                                                                        <th>{{$t("tool.paging")}}</th>
                                                                        <th width="16%">{{$t("tool.parameter")}}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="nowrap">/nbds/category</td>
                                                                        <td class="nowrap">GET</td>
                                                                        <td class="wordwrap"><a :href="link">NameUsage Page</a></td>
                                                                        <td>{{$t("tool.description_url_nbds_category")}}</td>
                                                                        <td class="nowrap">{{$t("tool.true")}}</td>
                                                                        <td class="wordwrap">
                                                                                <a href="#p_search">search</a>, <a href="#p_type">type</a>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </section>
                                                <section id="parameters" class="white-background page--developer__anchor">
                                                    <div class="container--narrow">
                                                        <h3>{{$t("tool.query_parameters_explained")}}</h3>
                                                        <p>{{$t("tool.description_category_parameters_explained")}}</p>
                                                        <table class="table table-bordered table-striped table-params">
                                                            <thead>
                                                            <tr>
                                                                <th width="25%" class="total">{{$t("tool.parameter")}}</th>
                                                                <th width="75%">{{$t("tool.description")}}</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td><a id="p_search"></a>search</td>
                                                                    <td>{{$t("tool.description_search")}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><a id="p_type"></a>type</td>
                                                                    <td>{{$t("tool.description_type")}}</td>
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
    import * as env from "../env.js";
    export default {
        data: function() {
            return {
                link: null,
                domain: null
            }
        },

        created() {
            this.link = env.endpointhttp + 'nbds/category',
            this.domain = window.location.hostname;
        }
    }
</script>

