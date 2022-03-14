<template>
    <div class="container">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <router-link v-bind:to="{name:'home'}" >
                <a class="navbar-brand .text-decoration-none">Youwitter</a>
    </router-link>
      <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#Nav1" aria-controls="Nav1"
        aria-expanded="false" aria-label="ナビゲーションの切替">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="Nav1">
        <ul class="navbar-nav mt-3">
            <li class="nav-item active">
                <router-link v-bind:to="{name:'user.register'}">
                    <a a class="nav-link" href="#">アカウント作成</a>
                </router-link>
            </li>
            <li class="nav-item">
                <router-link v-bind:to="{name:'user.login'}">
                    <a a class="nav-link" href="#">ログイン</a>
                </router-link>
            </li>
            <li class="nav-item">
                <router-link v-bind:to="{name:'user.myprofile'}">
                    <a class="nav-link" href="#">プロフィール</a>
                </router-link>
            </li>
            <li class="nav-item">
                <router-link v-bind:to="{name:'movieList.create'}">
                    <a class="nav-link" href="#">マイリストの作成</a>
                </router-link>
            </li>
        </ul>
      </div>
    </nav>
    </div>
</template>

<script>
    export default {}
</script>
