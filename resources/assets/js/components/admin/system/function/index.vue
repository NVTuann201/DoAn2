<template>
    
</template>

<script>
    export default {
        name: "index.vue",
        data: function () {
            return {
                typeNotification: null,
                textNotification: null,
                roles: null,
                users: null,
                page: 1,
                count: 0,
                search_role: null,
            }
        },
        mounted() {
            this.getRoles();
            this.getUser();
        },
        methods: {
            editUser(value){
                window.location.href = "/users/update/" + value;
            },
            deleteUser(value){
                axios
                    .delete(endpointhttp + 'users/delete/' + value, {
                    })
                    .then(
                        response => {
                            if (response.data.message == "message.success") {
                                this.action = true;
                                this.getUser();
                            } else {
                                this.action = true;
                                this.typeNotification = 1;
                                this.textNotification = "Đã có lỗi khi thực hiện tác vụ này.";
                            }
                        }
                    )
                    .catch(error => {
                        this.action = true;
                        this.typeNotification = 1;
                        this.textNotification = "Đã có lỗi khi thực hiện tác vụ này.";
                    })
                    .finally(() => {
                        this.loading = false;
                        this.typeNotification = null;
                        this.textNotification = null;
                    });
            },
            searchRole(value) {
                if(value){
                    this.search_role = value;
                }else{
                    this.search_role = null;
                };
                axios.get(env.endpointhttp + "users", {
                    params: {
                        search_role: this.search_role,
                        with: ['role']
                    }
                })
                    .then(response => {
                        this.users = response.data.result.data;
                        this.count = response.data.result.last_page;
                        console.log(this.users[0].name);
                        console.log(typeof response.data.result.data);
                        console.log(response.data.result.data);
                    });
            },
            getRoles() {
                axios.get(env.endpointhttp + "roles", {
                    params: {
                        count: ['users']
                    }
                })
                    .then(response => {
                        this.roles = response.data.result;
                        console.log(this.roles[0].name);
                        console.log(this.roles[0].users_count);
                    });
            },
            getUser() {
                axios.get(env.endpointhttp + "users", {
                    params: {
                        with: ['role']
                    }
                })
                    .then(response => {
                        this.users = response.data.result.data;
                        this.count = response.data.result.last_page;
                        console.log(this.users[0].name);
                        console.log(typeof response.data.result.data);
                        console.log(response.data.result.data);
                    });
            },
            clickCallback(pageNum) {
                this.page = pageNum;
                axios
                    .get(env.endpointhttp + "users" + "&search_role=" + this.search_role + "&page=" + this.page, {
                        params: {
                            with: ['role']
                        }
                    })
                    .then(response => {
                        this.data = response.data.result.data;
                        this.count = response.data.result.last_page;
                    })
                    .catch(error => {
                        console.log(error);
                    })
                    .finally();
            },
            goAddUser() {
                window.location.href = "users/add";
            }
        },
    }
</script>

<style scoped>

</style>