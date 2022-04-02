<template>
    <div>
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
                        console.log('logout');
                        this.$store.commit('logout');
                        this.$router.push({name:'user.login'});
                    })
            }
        }
    }
</script>
