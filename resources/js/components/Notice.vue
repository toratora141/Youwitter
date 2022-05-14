<template>
    <div>
        <div class="card-header">
            <h4 class="m-auto pt-2 pb-2 text-center">通知</h4>
        </div>
        <div class="mt-5 mb-3 text-center" v-show="!fetchEnd">
            <div class="spinner-border text-secondary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div v-if="hasNotice">
            <div class="card" v-for="notice in notices" :key="notice.key">
                <router-link style="text-decoration: none; color: rgb(20, 22, 25);" v-bind:to="{name:'user.profile', params:{accountName: notice.user.account_name}}">
                    <div class="d-flex flex-column align-items-center w-100 p-2">
                        <div class="d-flex flex-row w-100">
                            <img :src="'/storage/'+notice.user.icon" class="img-fluid img-thumbnail rounded-circle" style="width:50px; height:50px; object-fit:cover;">
                            <div class="d-flex flex-column p-1">
                                <h5>{{notice.user.account_name}}</h5>
                                <p class="m-0">{{notice.user.account_name}}</p>
                            </div>
                            <div class="" v-if="notice.type == 'follow'">
                                <p class="m-0 p-1">さんにフォローされました</p>
                            </div>
                            <div v-if="notice.type == 'good'">
                                <p class="m-0 p-1">さんがいいねしました</p>
                            </div>
                            <div class="flex-fill">
                                <p class="text-end p-1 m-0">{{diffTime(notice.created_at)}}</p>
                            </div>
                        </div>
                        <div class="w-100 p-2">
                            <div class="w-100 d-flex flex-row align-items-center justify-content-center" v-if="notice.type == 'good'">
                                <img class="img-fluid m-1"
                                    :src="'/storage/' + notice.good.video.thumbnail"
                                    style="width:50px; height: 50px; object-fit: cover"
                                />
                                <p class="w-50 fs-6 m-2">{{notice.good.video.title}}</p>
                            </div>
                        </div>
                    </div>
                </router-link>
            </div>
        </div>
        <div v-else>
            <div class="alert alert-secondary">
                通知はありません
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data:function() {
        return {
            hasNotice: true,
            notices: null,
            fetchEnd: false,
        }
    },
    created(){
        axios.get('/api/notice/fetch')
            .then((res) => {
                this.fetchEnd = true;
                this.notices = res.data.notices;
                this.hasNotice = true;
            })
    }
}
</script>
