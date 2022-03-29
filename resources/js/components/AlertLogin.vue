<template>
    <div class="alert alert-dark" role="alert" v-show="!isLoggedIn">
        ログインされていません。<br>
        <router-link v-bind:to="{name:'user.login'}">
            <button class="btn btn-secondary">ログイン</button>
        </router-link>

        <router-link v-bind:to="{name:'user.register'}">
            <button class="btn btn-secondary" href="#">アカウント作成</button>
        </router-link>
    </div>
</template>

<script>

export default ({
    data(){
        return {
            user: {},
            isLoggedIn: this.$store.state.isLoggedIn,
        }
    },
})
</script>
