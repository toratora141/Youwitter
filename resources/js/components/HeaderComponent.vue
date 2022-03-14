<template>
    <div class="container">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <!-- TOPは、ブラントとして常に表示 -->
      <router-link v-bind:to="{name:'home'}" >
                <a class="navbar-brand .text-decoration-none">Youwitter</a>
    </router-link>
      <!-- navbar-togglerでレスポンシブ -->
      <!-- レスポンシブ状態でないと、トグルアイコンは非表示になり、メニューが表示される -->
      <!-- レスポンシブになると、トグルアイコンが表示され、メニューは非表示になる -->
      <!-- data-bs-targetとidでコンテンツを連携させる -->
      <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#Nav1" aria-controls="Nav1"
        aria-expanded="false" aria-label="ナビゲーションの切替">
        <!-- ハンバーガーメニューでボタンを表示 navbar-toggler-icon -->
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- ボタンが押されたら、以下のメニューを開く、メニューはulとliを使う -->
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
            <li class="nav-item"><a class="nav-link" href="#">メニュー3</a></li>
        </ul>
      </div>
    </nav>
    </div>
</template>

<script>
    export default {}
</script>
