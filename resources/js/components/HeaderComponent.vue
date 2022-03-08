<template>
    <div class="container">
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark mt-3 mb-3">
        <div class="container-fluid">
            <router-link v-bind:to="{name:'home'}" >
                <a class="navbar-brand .text-decoration-none">DoSeeThis</a>
            </router-link>
            <ul class="navbar-nav">
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav">
                        <li class="nav-item active">
                            <router-link v-bind:to="{name:'user.register'}">
                                <button class="btn btn-success">新規登録</button>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link v-bind:to="{name:'user.login'}">
                                <button class="btn btn-success">ログイン</button>
                            </router-link>
                        </li>
                    </ul>
                </div>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        ドロップダウン
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#">メニュー1</a></li>
                        <li><a class="dropdown-item" href="#">メニュー2</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">その他</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        </nav>
    </div>
</template>

<script>
    export default {}
</script>
