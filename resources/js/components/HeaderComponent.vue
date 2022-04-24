<template>
    <div class="container w-100 p-0" >
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <router-link v-bind:to="{name:'home'}" >
                <a class="navbar-brand .text-decoration-none">Youwitter</a>
    </router-link>
      <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#Nav" aria-controls="Nav"
        aria-expanded="false" aria-label="ナビゲーションの切替">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="Nav" ref="nav">
        <ul class="navbar-nav mt-3" >
            <li class="nav-item"  v-if="$store.state.isLoggedIn">
                <router-link v-bind:to="{name:'user.profile', params:{accountName:$store.state.user.accountName}}">
                    <a class="nav-link" href="#">プロフィール</a>
                </router-link>
            </li>
            <li class="nav-item" >
                <router-link v-bind:to="{name:'movieList.create'}">
                    <a class="nav-link" href="#">マイリストの作成</a>
                </router-link>
            </li>
            <li class="nav-item" >
                <router-link v-bind:to="{name:'user.search'}">
                    <a a class="nav-link" href="#">検索</a>
                </router-link>
            </li>
            <li class="nav-item" >
                <router-link v-bind:to="{name:'user.notice'}">
                    <a a class="nav-link" href="#">通知</a>
                </router-link>
            </li>
            <li class="nav-item" >
                <router-link v-bind:to="{name:'user.setting'}">
                    <a class="nav-link" href="#">設定</a>
                </router-link>
            </li>
        </ul>
      </div>
    </nav>
    </div>
</template>

<script>
    export default {
        date: function() {
            return {
                isLoggedIn: false,
            }
        },
        created(){
        },
        methods:{
        }
    }
    $(document).on('click', '#Nav', function(){
        $('.navbar-collapse').collapse('hide');
    });
</script>
