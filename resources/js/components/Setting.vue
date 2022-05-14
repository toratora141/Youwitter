<template>
    <div>
        <!-- <router-link v-bind:to="{name:'user.login'}">
            <button class="btn btn-secondary">ログイン</button>
        </router-link>
        <button class="btn btn-secondary" v-on:click="logout">ログアウト</button> -->
        <div class="card">
            <div class="card-header m-0">
                <div class="card-title">
                    <h5 class="m-0">このサイトについて</h5>
                </div>
            </div>
            <div class="body p-2">
                    <p>個人学習用に作ったサイトのためバグが発生する恐れがありますが、下のボタンから再度ログインしなおしてお試しください。また、メールアドレスやGoogleアカウントなどの情報を保存しておりませんので、個人情報の流出等はありません。</p>
                    <button class="btn btn-secondary mb-4" v-on:click="logout">ログアウト</button>
                    <p>パスワードを忘れてしまった場合やご意見等ございましたら、お手数をお掛けしますが、お問い合わせ頂ければ幸いです。</p>
            </div>
        </div>
        <div class="card-footer">
            問い合わせ先<a href="https://twitter.com/taro10890" class="flowbtn fl_tw1 rounded text-white p-1 text-decoration-none ms-2" style="background-color:#55acee;"><i class="bi bi-twitter me-1"></i>taro10890</a>
        </div>
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
                    .catch((error) => {
                        this.$store.commit('logout');
                        this.$router.push({name:'user.login'});
                    })
            }
        }
    }
</script>
