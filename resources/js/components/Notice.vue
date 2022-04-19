<template>
    <div>
        <div v-if="hasNotice">
            <div v-for="notice in notices" :key="notice.key">
                <router-link style="text-decoration: none; color: rgb(20, 22, 25);" v-bind:to="{name:'user.profile', params:{accountName: notice.user.account_name}}">
                    <div class="d-flex flex-row align-items-center">
                        <img :src="'/storage/'+notice.user.icon" class="img-fluid img-thumbnail rounded-circle" style="width:100px; height:100px; object-fit:cover;">
                        <div class="d-flex flex-column">
                            <h5>{{notice.user.account_name}}</h5>
                            <p>{{notice.user.account_name}}</p>
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
        }
    },
    created(){
        axios.get('/api/notice/fetch')
            .then((res) => {
                this.notices = res.data.notices;
            })
    }
}
</script>
