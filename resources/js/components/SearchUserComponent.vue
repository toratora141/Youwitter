<template>
    <div class="content w-100 m-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="m-auto pt-2 pb-2 text-center">検索</h4>
            </div>
            <div class="card-content">
                <div class="card-body text-center">
                    <label for="search"></label>
                    <input type="text" id="search" placeholder="ユーザID" v-model="searchKeyword">
                    <label for="search" v-if="errors.search" v-text="errors.search"></label>
                    <button v-on:click="search" class="btn btn-secondary">検索</button>
                </div>
            </div>
        </div>
        <div class="mt-5 mb-3 text-center" v-show="!fetchEnd">
            <div class="spinner-border text-secondary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div class="search-list card" v-if="endSearch">
            <div class="card-content">
                <div class="card-body">
                    <div v-for="user in users" :key="user.id">
                        <router-link style="text-decoration: none; color: rgb(20, 22, 25);" v-bind:to="{name:'user.profile', params:{accountName: user.account_name}}">
                            <div class="d-flex flex-row align-items-center">
                                <img :src="'/storage/' +user.icon" class="img-fluid img-thumbnail rounded-circle" style="width:50px; height:50px; object-fit:cover;">
                                <div class="d-flex flex-column">
                                    <h5>{{user.account_name}}</h5>
                                    <p>{{user.display_name}}</p>
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
            fetchEnd: true,
        }
    },
    created(){
        this.searchKeyword = this.$store.state.searchKeyword;
        if(!(this.$store.state.searchKeyword === null)){
            this.endSearch = true;
            this.users = this.$store.state.searchResult;
        }
    },
    methods:{
        search(){
            this.fetchEnd = false;
            this.users = null;
            axios.get('/api/searchUser', {params:{
                searchKeyword: this.searchKeyword
            }})
                .then((res) => {
                    this.endSearch = true;
                    this.fetchEnd = true;
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
