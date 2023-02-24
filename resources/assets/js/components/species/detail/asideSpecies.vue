<template>
    <aside class="site-drawer is-locked" style="overflow-y: scroll">
        <div class="site-drawer__content">
            <div class="site-drawer__header">
                <div class="site-drawer__bar">
                    <div class="site-drawer__bar__title">
                        <span class="ng-scope">{{ $t("nbds_lang.classification") }}</span>
                    </div>
                </div>
            </div>
            <div class="site-drawer__section">
                <div class="m-05">
                    <form class="search-box">
                        <input type="text" v-model="keySearchSpecies" class="fit-suggestions--searchBox" :placeholder="$t('tool.select_species')" v-on:keyup.enter="searchSpecies()">
                        <ul class="dropdown-menu">
                        </ul>
                    </form>
                </div>
                <div class="taxonomyBrowser">
                    <div>
                        <div>
                            <ul>
                                <li v-for="(item) in kingdom"
                                    class="taxonomyBrowser__parent"
                                    :class="(value.result.rank == 'kingdom') ? 'taxonomyBrowser__current': ''">
                                    <a class="taxonomyBrowser__parent noUnderline inherit" :href="'/detail/indexspecies/' + item.id">
                                        <div class="taxonomyBrowser__term taxonomyBrowser__rank">{{item.rank}}</div>
                                        <div class="taxonomyBrowser__description">
                                            <span class="sciname ng-binding">{{item.name}}</span>
                                        </div>
                                    </a>
                                    <div v-if="synonyms.length > 0 && (value.result.rank == 'kingdom')" class="taxonomyBrowser__synonyms ng-scope">
                                        <div class="taxonomyBrowser__subheadline m-t-1"></div>
                                        <div class="taxonomyBrowser__synonym__bordered">
                                            <ul>
                                                <li v-for="(item1) in synonyms" class="ng-scope">
                                                    <div class="taxonomyBrowser__description taxonomyBrowser__synonym">
                                                        <span class="synonymSymbol ng-binding">=</span>
                                                        <div>
                                                            <a :href="'/detail/indexspecies/' + item1.id" class="inherit">
                                                                <span dir="auto" class="sciname ng-binding">{{item1.name}}</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div v-if="accepted.length>0 && (value.result.rank == 'kingdom')" class="taxonomyBrowser__synonyms ng-scope">
                                        <div class="taxonomyBrowser__subheadline m-t-1 ng-scope">{{$t("nbds_lang.accepted_name")}}</div>
                                        <div class="taxonomyBrowser__synonym__bordered">
                                            <ul>
                                                <li v-for="(item2) in accepted" class="ng-scope">
                                                    <div class="taxonomyBrowser__description taxonomyBrowser__synonym">
                                                        <span class="synonymSymbol ng-binding">=</span>
                                                        <div>
                                                            <a :href="'/detail/indexspecies/' + item2.id" class="inherit">
                                                                <span dir="auto" class="sciname ng-binding">{{item2.name}}</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                <li v-for="(item) in phylum"
                                    class="taxonomyBrowser__parent ng-scope"
                                    :class="(value.result.rank == 'phylum') ? 'taxonomyBrowser__current': ''"
                                    >
                                    <a class="taxonomyBrowser__parent noUnderline inherit" :href="'/detail/indexspecies/' + item.id">
                                        <div class="taxonomyBrowser__term taxonomyBrowser__rank ng-scope">{{item.rank}}</div>
                                        <div class="taxonomyBrowser__description">
                                            <span class="sciname ng-binding">{{item.name}}</span>
                                        </div>
                                    </a>
                                    <div v-if="synonyms.length > 0 && (value.result.rank == 'phylum')" class="taxonomyBrowser__synonyms ng-scope">
                                        <div class="taxonomyBrowser__subheadline m-t-1"></div>
                                        <div class="taxonomyBrowser__synonym__bordered">
                                            <ul>
                                                <li v-for="(item1) in synonyms" class="ng-scope">
                                                    <div class="taxonomyBrowser__description taxonomyBrowser__synonym">
                                                        <span class="synonymSymbol ng-binding">=</span>
                                                        <div>
                                                            <a :href="'/detail/indexspecies/' + item1.id" class="inherit">
                                                                <span dir="auto" class="sciname ng-binding">{{item1.name}}</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div v-if="accepted.length>0 && (value.result.rank == 'phylum')" class="taxonomyBrowser__synonyms ng-scope">
                                        <div class="taxonomyBrowser__subheadline m-t-1 ng-scope">{{$t("nbds_lang.accepted_name")}}</div>
                                        <div class="taxonomyBrowser__synonym__bordered">
                                            <ul>
                                                <li v-for="(item2) in accepted" class="ng-scope">
                                                    <div class="taxonomyBrowser__description taxonomyBrowser__synonym">
                                                        <span class="synonymSymbol ng-binding">=</span>
                                                        <div>
                                                            <a :href="'/detail/indexspecies/' + item2.id" class="inherit">
                                                                <span dir="auto" class="sciname ng-binding">{{item2.name}}</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                <li v-for="(item) in listClass"
                                    class="taxonomyBrowser__parent ng-scope"
                                    :class="(value.result.rank == 'class') ? 'taxonomyBrowser__current': ''"
                                    >
                                    <a class="taxonomyBrowser__parent noUnderline inherit" :href="'/detail/indexspecies/' + item.id">
                                        <div class="taxonomyBrowser__term taxonomyBrowser__rank ng-scope">{{item.rank}}</div>
                                        <div class="taxonomyBrowser__description">
                                            <span class="sciname ng-binding">{{item.name}}</span>
                                        </div>
                                    </a>
                                    <div v-if="synonyms.length > 0 && (value.result.rank == 'class')" class="taxonomyBrowser__synonyms ng-scope">
                                        <div class="taxonomyBrowser__subheadline m-t-1"></div>
                                        <div class="taxonomyBrowser__synonym__bordered">
                                            <ul>
                                                <li v-for="(item1) in synonyms" class="ng-scope">
                                                    <div class="taxonomyBrowser__description taxonomyBrowser__synonym">
                                                        <span class="synonymSymbol ng-binding">=</span>
                                                        <div>
                                                            <a :href="'/detail/indexspecies/' + item1.id" class="inherit">
                                                                <span dir="auto" class="sciname ng-binding">{{item1.name}}</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div v-if="accepted.length>0 && (value.result.rank == 'class')" class="taxonomyBrowser__synonyms ng-scope">
                                        <div class="taxonomyBrowser__subheadline m-t-1 ng-scope">{{$t("nbds_lang.accepted_name")}}</div>
                                        <div class="taxonomyBrowser__synonym__bordered">
                                            <ul>
                                                <li v-for="(item2) in accepted" class="ng-scope">
                                                    <div class="taxonomyBrowser__description taxonomyBrowser__synonym">
                                                        <span class="synonymSymbol ng-binding">=</span>
                                                        <div>
                                                            <a :href="'/detail/indexspecies/' + item2.id" class="inherit">
                                                                <span dir="auto" class="sciname ng-binding">{{item2.name}}</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                <li v-for="(item) in order"
                                    class="taxonomyBrowser__parent ng-scope"
                                    :class="(value.result.rank == 'order') ? 'taxonomyBrowser__current': ''"
                                    >
                                    <a class="taxonomyBrowser__parent noUnderline inherit" :href="'/detail/indexspecies/' + item.id">
                                        <div class="taxonomyBrowser__term taxonomyBrowser__rank ng-scope">{{item.rank}}</div>
                                        <div class="taxonomyBrowser__description">
                                            <span class="sciname ng-binding">{{item.name}}</span>
                                        </div>
                                    </a>
                                    <div v-if="synonyms.length > 0 && (value.result.rank == 'order')" class="taxonomyBrowser__synonyms ng-scope">
                                        <div class="taxonomyBrowser__subheadline m-t-1"></div>
                                        <div class="taxonomyBrowser__synonym__bordered">
                                            <ul>
                                                <li v-for="(item1) in synonyms" class="ng-scope">
                                                    <div class="taxonomyBrowser__description taxonomyBrowser__synonym">
                                                        <span class="synonymSymbol ng-binding">=</span>
                                                        <div>
                                                            <a :href="'/detail/indexspecies/' + item1.id" class="inherit">
                                                                <span dir="auto" class="sciname ng-binding">{{item1.name}}</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div v-if="accepted.length>0 && (value.result.rank == 'order')" class="taxonomyBrowser__synonyms ng-scope">
                                        <div class="taxonomyBrowser__subheadline m-t-1 ng-scope">{{$t("nbds_lang.accepted_name")}}</div>
                                        <div class="taxonomyBrowser__synonym__bordered">
                                            <ul>
                                                <li v-for="(item2) in accepted" class="ng-scope">
                                                    <div class="taxonomyBrowser__description taxonomyBrowser__synonym">
                                                        <span class="synonymSymbol ng-binding">=</span>
                                                        <div>
                                                            <a :href="'/detail/indexspecies/' + item2.id" class="inherit">
                                                                <span dir="auto" class="sciname ng-binding">{{item2.name}}</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                <li v-for="(item) in family"
                                    class="taxonomyBrowser__parent ng-scope"
                                    :class="(value.result.rank == 'family') ? 'taxonomyBrowser__current': ''"
                                    >
                                    <a class="taxonomyBrowser__parent noUnderline inherit" :href="'/detail/indexspecies/' + item.id">
                                        <div class="taxonomyBrowser__term taxonomyBrowser__rank ng-scope">{{item.rank}}</div>
                                        <div class="taxonomyBrowser__description">
                                            <span class="sciname ng-binding">{{item.name}}</span>
                                        </div>
                                    </a>
                                    <div v-if="synonyms.length > 0 && (value.result.rank == 'family')" class="taxonomyBrowser__synonyms ng-scope">
                                        <div class="taxonomyBrowser__subheadline m-t-1"></div>
                                        <div class="taxonomyBrowser__synonym__bordered">
                                            <ul>
                                                <li v-for="(item1) in synonyms" class="ng-scope">
                                                    <div class="taxonomyBrowser__description taxonomyBrowser__synonym">
                                                        <span class="synonymSymbol ng-binding">=</span>
                                                        <div>
                                                            <a :href="'/detail/indexspecies/' + item1.id" class="inherit">
                                                                <span dir="auto" class="sciname ng-binding">{{item1.name}}</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div v-if="accepted.length>0 && (value.result.rank == 'family')" class="taxonomyBrowser__synonyms ng-scope">
                                        <div class="taxonomyBrowser__subheadline m-t-1 ng-scope">{{$t("nbds_lang.accepted_name")}}</div>
                                        <div class="taxonomyBrowser__synonym__bordered">
                                            <ul>
                                                <li v-for="(item2) in accepted" class="ng-scope">
                                                    <div class="taxonomyBrowser__description taxonomyBrowser__synonym">
                                                        <span class="synonymSymbol ng-binding">=</span>
                                                        <div>
                                                            <a :href="'/detail/indexspecies/' + item2.id" class="inherit">
                                                                <span dir="auto" class="sciname ng-binding">{{item2.name}}</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                <li v-for="(item) in genus"
                                    class="taxonomyBrowser__parent ng-scope"
                                    :class="(value.result.rank == 'genus') ? 'taxonomyBrowser__current': ''"
                                    >
                                    <a class="taxonomyBrowser__parent noUnderline inherit" :href="'/detail/indexspecies/' + item.id">
                                        <div class="taxonomyBrowser__term taxonomyBrowser__rank ng-scope">{{item.rank}}</div>
                                        <div class="taxonomyBrowser__description">
                                            <span class="sciname ng-binding">{{item.name}}</span>
                                        </div>
                                    </a>
                                    <div v-if="synonyms.length > 0 && (value.result.rank == 'genus')" class="taxonomyBrowser__synonyms ng-scope">
                                        <div class="taxonomyBrowser__subheadline m-t-1"></div>
                                        <div class="taxonomyBrowser__synonym__bordered">
                                            <ul>
                                                <li v-for="(item1) in synonyms" class="ng-scope">
                                                    <div class="taxonomyBrowser__description taxonomyBrowser__synonym">
                                                        <span class="synonymSymbol ng-binding">=</span>
                                                        <div>
                                                            <a :href="'/detail/indexspecies/' + item1.id" class="inherit">
                                                                <span dir="auto" class="sciname ng-binding">{{item1.name}}</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div v-if="accepted.length>0 && (value.result.rank == 'genus')" class="taxonomyBrowser__synonyms ng-scope">
                                        <div class="taxonomyBrowser__subheadline m-t-1 ng-scope">{{$t("nbds_lang.accepted_name")}}</div>
                                        <div class="taxonomyBrowser__synonym__bordered">
                                            <ul>
                                                <li v-for="(item2) in accepted" class="ng-scope">
                                                    <div class="taxonomyBrowser__description taxonomyBrowser__synonym">
                                                        <span class="synonymSymbol ng-binding">=</span>
                                                        <div>
                                                            <a :href="'/detail/indexspecies/' + item2.id" class="inherit">
                                                                <span dir="auto" class="sciname ng-binding">{{item2.name}}</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                <li v-for="(item, index) in species"
                                    class="taxonomyBrowser__parent ng-scope"
                                    :class="(value.result.rank == 'species') ? 'taxonomyBrowser__current': ''"
                                    v-if="index <= maxSpecies">
                                    <a class="taxonomyBrowser__parent noUnderline inherit" :href="'/detail/indexspecies/' + item.id">
                                        <div class="taxonomyBrowser__term taxonomyBrowser__rank ng-scope">{{item.rank}}</div>
                                        <div class="taxonomyBrowser__description">
                                            <span class="sciname ng-binding">{{item.name}}</span>
                                        </div>
                                    </a>
                                    <div v-if="synonyms.length > 0 && (value.result.rank == 'species')" class="taxonomyBrowser__synonyms ng-scope">
                                        <div class="taxonomyBrowser__subheadline m-t-1"></div>
                                        <div class="taxonomyBrowser__synonym__bordered">
                                            <ul>
                                                <li v-for="(item1) in synonyms" class="ng-scope">
                                                    <div class="taxonomyBrowser__description taxonomyBrowser__synonym">
                                                        <span class="synonymSymbol ng-binding">=</span>
                                                        <div>
                                                            <a :href="'/detail/indexspecies/' + item1.id" class="inherit">
                                                                <span dir="auto" class="sciname ng-binding">{{item1.name}}</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div v-if="accepted.length>0 && (value.result.rank == 'species')" class="taxonomyBrowser__synonyms ng-scope">
                                        <div class="taxonomyBrowser__subheadline m-t-1 ng-scope">{{$t("nbds_lang.accepted_name")}}</div>
                                        <div class="taxonomyBrowser__synonym__bordered">
                                            <ul>
                                                <li v-for="(item2) in accepted" class="ng-scope">
                                                    <div class="taxonomyBrowser__description taxonomyBrowser__synonym">
                                                        <span class="synonymSymbol ng-binding">=</span>
                                                        <div>
                                                            <a :href="'/detail/indexspecies/' + item2.id" class="inherit">
                                                                <span dir="auto" class="sciname ng-binding">{{item2.name}}</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                <div class="m-t-2 text-center ng-scope">
                                    <button v-if="index >= maxSpecies" @click="maxSpecies= maxSpecies + 10" class="gb-button--discreet ng-scope">{{ $t("nbds_lang.more") }}</button>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </aside>
</template>
<script>
    import Multiselect from 'vue-multiselect'

    export default {
        name: "asideSpecies",
        components: {
            Multiselect
        },
        props: {
            'value': {
                type: Object
            }
        },
        data: function () {
            return {
                keySearchSpecies: null,
                isLoading: false,
                maxSpecies: 10,
                kingdom: [],
                phylum: [],
                listClass: [],
                order: [],
                family: [],
                genus: [],
                species: [],
                synonyms: [],
                accepted: [],                
                index: null,
            };
        },
        created() {
            console.log("value::", this.value);
            this.getData();
        },
        methods: {
            getData(){
                if(this.value){
                    let children = this.value.children;
                    for(let i in children){
                        if(children[i].rank == 'phylum'){
                            this.phylum.push(this.value.children[i]);
                        }
                        if(children[i].rank == 'class'){
                            this.listClass.push(this.value.children[i]);
                        }
                        if(children[i].rank == 'order'){
                            this.order.push(this.value.children[i]);
                        }
                        if(children[i].rank == 'family'){
                            this.family.push(this.value.children[i]);
                        }
                        if(children[i].rank == 'genus'){
                            this.genus.push(this.value.children[i]);
                        }
                        if(children[i].rank == 'species'){
                            this.species.push(this.value.children[i]);
                        }
                    }
                    if(this.value.kingdom){
                        this.kingdom.push(this.value.kingdom[0]);
                    }
                    if(this.value.phylum){
                        this.phylum.push(this.value.phylum[0]);
                    }
                    if(this.value.class){
                        this.listClass.push(this.value.class[0]);
                    }
                    if(this.value.order){
                        this.order.push(this.value.order[0]);
                    }
                    if(this.value.family){
                        this.family.push(this.value.family[0]);
                    }
                    if(this.value.genus){
                        this.genus.push(this.value.genus[0]);
                    }
                    if(this.value.species){
                        this.species.push(this.value.species[0]);
                    }
                    if(this.value.accepted){
                        this.accepted = this.value.accepted;
                    }
                    if(this.value.synonyms){
                        this.synonyms = this.value.synonyms;
                    }
                }
            },
            searchSpecies(){
                window.location.href = "/search/species";
                this.setKeySpecies(this.keySearchSpecies);
            },
            setKeySpecies(value){
                this.$store.commit("setKeySearch", {amount: value});
            }
        },
        watch: {
            value: function(){
                this.getData();
            },
        },
    }
</script>