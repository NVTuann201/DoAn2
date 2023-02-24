<template>
    <div class="horizontal-stripe article-header white-background">
        <div class="container--desktop">
            <div class="row">
                <header class="col-xs-12 text-center">
                    <nav class="article-header__category article-header__category--deep">
                        <span class="article-header__category__upper">
                            <a href="#">{{organization_type}}</a>
                        </span>
                        <span class="article-header__category__lower">{{$t('nbds_lang.joined') + ': ' + created_at}}
                        </span>
                    </nav>
                    <h1 class="text-center">
                        <span>{{name}}</span>
                    </h1>                    
                </header>
            </div>
        </div>
    </div>
</template>

<script>
import VueMoment from 'vue-moment'

export default {
    props: ['value'],
    data: function() {
        return {
            organization_type: '---',
            name: '---',
            created_at: null,            
        }
    },
    mounted() {
        if(this.value.organization_type){
            this.organization_type = this.value.organization_type;            
        }
        if(this.value.name){            
            this.name = this.value.name;                     
        }
        if(this.value.created_at){          
            this.created_at = this.$moment(this.value.created_at).format('LL');                      
        }        
    }
}
</script>

