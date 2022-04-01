<template>
<!-- <div class="mx-auto p-2 justify-content-center w-50" style="max-width: 600px;"> -->
<div>
    <div class="alert alert-dark" role="alert" v-show="!isLoggedIn">
        ログインされていません。<br>
        <router-link v-bind:to="{name:'user.login'}">
            <button class="btn btn-secondary">ログイン</button>
        </router-link>

        <router-link v-bind:to="{name:'user.register'}">
            <button class="btn btn-secondary" href="#">アカウント作成</button>
        </router-link>
    </div>
    <div class="card" v-for="follow in follows" :key="follow.account_name">
        <div class="card-body">
            <div class="card-title d-flex flex-column" style="height:250px">
                <div class="text-start d-flex flex-column">
                    <img :src="'/storage/' + follow.user.icon" class="img-fluid img-thumbnail rounded-circle" style="width:100px; height:100px; object-fit:cover;">
                    <div class="mt-3">
                        <h3 v-if="follow.user.display_name" v-text="follow.user.display_name"></h3>
                        <h6 v-if="follow.user.account_name" v-text="follow.user.account_name"></h6>
                    </div>
                </div>
            </div>
            <div class="card-text">
                <movie-list-component ref="movieList"></movie-list-component>
            </div>
        </div>
    </div>
</div>
</template>

<script>
// import MovieListComponent from './MovieListComponent.vue';
  export default {
    data() {
      return {
        user: this.$store.state.user,
        isLoggedIn: this.$store.state.isLoggedIn,
        error: {},
        follows: {},
        movieList: {},
      }
    },
    mounted(){
        axios.get('/api/user/follow/fetch')
            .then((res) => {
                this.follows = res.data.follows;
                    console.log(this.$refs);
                    var count = 0;
                    this.follows.forEach(follow => {
                        this.$nextTick(()=>{
                            var user = follow.user_id
                            this.$refs.movieList[count].fetch(follow.videoList, follow.videos);
                        });
                    });
            })
            .catch((error) => {
                console.log(error);
            })
    },
    created() {
        // console.log(this.$store.state.isLoggedIn);
        // console.log(this.$store.state.user);
        // console.log(this.$store.state.videoLists);
        // console.log(this.$store.state.videos);
        // axios.get('/api/user/follow/fetch')
        //     .then((res) => {
        //         this.follows = res.data.follows;
        //     })
        //     .catch((error) => {
        //         console.log(error);
        //     })
    },
  }
</script>
