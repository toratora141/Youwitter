<template>
    <div class="content">
        <div class="card">
            <div class="card-header">
                検索
            </div>
            <div class="card-content">
                <div class="card-body">
                    <label for="search"></label>
                    <input type="text" id="search" v-model="searchKeyword">
                    <label for="search" v-if="errors.search" v-text="errors.search"></label>
                    <button v-on:click="search" class="btn btn-secondary">検索</button>
                </div>
            </div>
        </div>
        <div class="search-list card">
            <div class="card-content" v-if="endSearch">
                <div class="card-body">
                    <div v-for="user in users" :key="user.id">
                        <router-link style="text-decoration: none; color: rgb(20, 22, 25);" v-bind:to="{name:'user.profile', params:{accountName: user.account_name}}">
                            <div class="d-flex flex-row align-items-center">
                                <img :src="'/storage/' +user.icon" class="img-fluid img-thumbnail rounded-circle" style="width:100px; height:100px; object-fit:cover;">
                                <div class="d-flex flex-column">
                                    <h5>{{user.account_name}}</h5>
                                    <p>{{user.account_name}}</p>
                                </div>
                            </div>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    data:function() {
        return {
            searchKeyword: null,
            users: {},
            errors: {},
            endSearch: false,
        }
    },
    mounted(){
        },
    created(){
        this.searchKeyword = this.$store.state.searchKeyword;
        if(!(this.$store.state.searchKeyword == null)){
            this.endSearch = true;
            this.users = this.$store.state.searchReuslt;
        }
    },
    methods:{
        search(){
            axios.get('/api/searchUser', {params:{
                searchKeyword: this.searchKeyword
            }})
                .then((res) => {
                    this.endSearch = true;
                    var commitParam = {
                        result: res.data.users,
                        keyword:this.searchKeyword
                    };
                    this.$store.commit('search',commitParam);
                    this.users = res.data.users;
                });
        },
    }
};
</script>
