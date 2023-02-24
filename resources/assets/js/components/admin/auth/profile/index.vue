<template>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-xs-12">
            <div class="white-box">
                <ul class="nav nav-tabs tabs customtab">
                    <li class="tab active">
                        <a href="#profile" data-toggle="tab"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">{{ $t("admin.profile.profile") }}</span> </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <ProfileSetting class="active" :value="value" :imageurl="image"></ProfileSetting>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import ProfileSetting from "../components/settings.vue";    

    export default {
        components: {
            ProfileSetting           
        },
        props: {
            value: {type: String},
            image: {type: String}
        }
    }
</script>


