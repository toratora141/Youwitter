<template>
    <div>
        <router-link v-bind:to="{name:'user.login'}">
            <button class="btn btn-secondary">ログイン</button>
        </router-link>
        <button class="btn btn-secondary" v-on:click="logout">ログアウト</button>
    </div>
</template>

<script>
    export default {
        data(){
            return {

            }
        },
        methods: {
            logout(){
                axios.post('/api/user/logout')
                    .then((res)=>{
                        this.$store.commit('logout');
                        this.$router.push({name:'user.login'});
                    })
            }
        }
    }
</script>
