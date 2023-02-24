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
                                                        class="article-header__category__lower">GBIF</span>
                                                </nav>
                                                <h1 class="text-center">
                                                    <p>Species</p>
                                                </h1>
                                                <div class="article-header__intro">
                                                    <p>http://api.gbif.org/v1/</p>
                                                </div>
                                                <div class="home__header__search-bar search-box" style="margin-left: 30px">
                                                    <input v-on:keyup.enter="searchAll()" type="text" :placeholder="$t('tool.search_by_scientific_name')" v-model="keyword">
                                                    <a @click="searchAll()" style="cursor:pointer" class="search-box__submit gb-icon-search2 inherit noUnderline" id="searchall">
                                                        <span class="sr-only">Search</span>
                                                    </a>
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
                                                        <a href="/tool/species">Species</a>
                                                    </li>
                                                    <li class="tab">
                                                        <a href="/tool/occurrence">Occurrence</a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </section>
                                <section class="white-background">
                                    <div class="container--normal" style="max-width: 1155px">
                                        <div class="scrollable">
                                            <div class="table-responsive">
                                                <h3>{{$t("tool.species_data")}}</h3>
                                                <p>{{$t("tool.data_take")}} <a href="https://www.gbif.org/developer/species" target="_blank">https://www.gbif.org/developer/species</a></p>
                                                <table class="table table-bordered table-striped m-t-30 table-hover contact-list footable footable-1 footable-paging footable-paging-center breakpoint-lg">
                                                    <thead>
                                                        <tr class="footable-header">
                                                            <th>{{$t("tool.stt")}}</th>
                                                            <th>{{$t("tool.scientific_name")}}</th>
                                                            <th style="vertical-align: middle">{{$t("tool.kingdom")}}</th>
                                                            <th style="vertical-align: middle">{{$t("tool.phylum")}}</th>
                                                            <th style="vertical-align: middle">{{$t("tool.class")}}</th>
                                                            <th style="vertical-align: middle">{{$t("tool.order")}}</th>
                                                            <th style="vertical-align: middle">{{$t("tool.family")}}</th>
                                                            <th style="vertical-align: middle">{{$t("tool.genus")}}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-if="!species">
                                                            <td colspan="11">
                                                                <components-loading></components-loading>
                                                            </td>
                                                        </tr>
                                                        <tr v-if="species && species.length == 0">
                                                            <td colspan="11" class="emptyInfomation">
                                                                <h5>{{ $t("admin.error.no_data") }}</h5>
                                                            </td>
                                                        </tr>
                                                        <tr v-for="(item, index) in species">
                                                            <td>
                                                                <span class="text-center">{{(page - 1)*20 + index + 1}}</span>
                                                            </td>
                                                            <td>
                                                                <div style="white-space: initial">
                                                                    {{item.scientificName}}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                {{item.kingdom}}
                                                            </td>
                                                            <td>
                                                                {{item.phylum}}
                                                            </td>
                                                            <td>
                                                                {{item.class}}
                                                            </td>
                                                            <td>
                                                                {{item.order}}
                                                            </td>
                                                            <td>{{item.family}}</td>
                                                            <td>{{item.genus}}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <tr class="footable-paging" v-if="species && species.length != 0">
                                            <td colspan="6">
                                                <paginate v-model="page" :page-count="this.count" :page-range="3"
                                                    :margin-pages="2" :click-handler="clickCallback" :prev-text="'‹‹'"
                                                    :next-text="'››'" :container-class="'pagination'"
                                                    :page-class="'page-item'"></paginate>
                                            </td>
                                        </tr>
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
    import * as routes from "../../routes.js";
    export default {
        data: function() {
            return {
                species: null,
                page: 1,
                keyword: '',
                count: 5000,
            }
        },

        created() {
            axios
                .get(routes.gettoolspecies)
                .then(response => {
                    this.species = response.data.results
                })
        }, 

        methods: {
            clickCallback(pageNum) {
                this.page = pageNum;
                axios   
                    .get(routes.gettoolspecies, {
                        params: {
                            q: this.keyword,
                            offset: (this.page-1)*20
                        }
                    })
                    .then(response => {
                        this.species = response.data.results
                    })
            },
            searchAll() {
                axios
                    .get(routes.gettoolspecies, {
                        params: {
                            q: this.keyword
                        }
                    })
                    .then(response => {
                        this.species = response.data.results
                        this.count = response.data.count <= 1000000 ? Math.ceil(response.data.count/20):5000
                        this.page = 1
                    })
            }
        },
    }
</script>

