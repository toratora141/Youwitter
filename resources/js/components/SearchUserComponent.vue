<template>
    <div class="content">
        <div class="card">
            <div class="card-header">
                検索
            </div>
            <div class="card-content">
                <div class="card-body">
                    <label for="search"></label>
                    <input type="text" id="search" v-on:input="setKeyword">
                    <label for="search" v-if="errors.search" v-text="errors.search"></label>
                    <button v-on:click="search" class="btn btn-secondary">検索</button>
                </div>
            </div>
        </div>
        <div class="search-list card">
            <div class="card-content">
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
        }
    },
    methods:{
        search(){
            axios.get('/api/searchUser', {params:{
                searchKeyword: this.searchKeyword
            }})
                .then((res) => {
                    this.users = res.data.users;
                });
        },
        setKeyword(event){
            this.searchKeyword = event.target.value;
            console.log(this.searchKeyword);
        }
    }
};
</script>
