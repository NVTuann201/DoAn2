<template>
    <nav class="submenu submenu--tabs">
        <ul class="nav-parts">
            <li class="part-name" v-if="this.tabs.length > 0">
                <ul class="nav-chapters">                                
                    <li class="nav-chapter" v-for="(item, index) in this.tabs" v-bind:class="{ active: item['active'] }">
                        <a :href="'#' + item.key" @click="activeTab(index)" class="link-tab">{{item.title}}</a>
                    </li>                                        
                </ul>
            </li>
        </ul>
    </nav>
</template>

<script>
export default {
    props: {
        'value': {
            type: Array,
            required: true
        },
        'active': {
            type: Number,
            default: 0
        }
    },
    data: function () {
        return {
            tabs: [],            
        }
    },
    created() {   
        if(this.value){
            this.value.forEach(function(currentValue, index, arr) {                 
               if(index == this){
                   currentValue.active = true; 
               }
               else{
                   currentValue.active = false;  
               }      
            }, this.active);
            for (let index = 0; index < this.value.length; index++) {                
                this.tabs.push(Object.assign({}, this.value[index]));                
            }            
        }        
    },
    methods: {
        activeTab(index) {    
            this.tabs.forEach(function(value, index){                    
                if(value.active){
                    value.active = false;                        
                    return;                       
                }
            });                 
            this.tabs[index].active = true; 
            this.$emit('input', index);                                 
        }
    }
}
</script>

<style scoped>
    .submenu-wrapper .submenu .nav-chapter.active a {
        border-left: 2px solid #09c;
    } 
    .link-tab {
        text-decoration: none;
    }
</style>

