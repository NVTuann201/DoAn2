<template>
    <div>
        <div class="modal" style="padding-top: 20%; display:block" v-show="show_pop_up">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="col-md-10">
                            <label class="modal-title">{{title}}</label>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="close" @click="closePopUp()">&times;</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-6" style="text-align: left;">
                            <button type="button" class="btn btn-default" @click="closePopUp()">Hủy</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-danger" @click="accept()"
                                data-dismiss="modal">Xóa</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <components-loading-page v-if="loading"></components-loading-page>
        <components-notifications :typeInput="typeNotification" :textInput="textNotification">
        </components-notifications>
    </div>
</template>

<script>
    export default {
        props: {
            'title': {
                default: 'Filter',
                type: String,
            },
            'data': {
            },
            'route_link': {
                required: true,
                type: String,
            },
            'type_pop_up': {
                type: String,
            },
            'show_pop_up': {
                default: false,
                type: Boolean
            }
        },
        data: function () {
            return {
                typeNotification: null,
                textNotification: null,
                loading: false,
            };
        },
        methods: {
            accept() {
                this.loading = true;
                axios
                    .delete(this.route_link + this.data, {
                    })
                    .then(
                        response => {
                            if (response.data.message == "message.success") {
                                this.loading = false;
                                this.$emit('showPopUp', false);
                                this.$emit('resetData', true);
                            } else {
                                this.typeNotification = 1;
                                this.textNotification = "Đã có lỗi khi thực hiện tác vụ này.";
                                this.loading = false;
                                this.$emit('showPopUp', false);
                                this.$emit('resetData', true);
                            }
                        }
                    )
                    .catch(error => {
                        this.typeNotification = 1;
                        this.textNotification = "Đã có lỗi khi thực hiện tác vụ này.";
                        this.loading = false;
                        this.$emit('showPopUp', false);
                        this.$emit('resetData', true);
                    })
                    .finally(() => {
                        this.typeNotification = null;
                        this.textNotification = null;
                        this.loading = false;
                        this.$emit('showPopUp', false);
                        this.$emit('resetData', true);
                    });
            },
            closePopUp() {
                this.$emit('showPopUp', false);
            },
        },
        watch: {
        },
        computed: {
        }
    }
</script>