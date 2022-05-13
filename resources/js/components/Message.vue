<template>
    <div>
        <div class="mt-5 mb-5 text-center" v-show="!fetchEnd">
            <div class="spinner-border text-secondary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div class="card min-vh-100" v-if="fetchEnd">
            <div class="card-content">
                <div class="card-header" v-for="user in users" :key="user.account_name">
                    <div class="d-flex flex-row align-items-center" v-if="user.user.account_name != myUser.accountName">
                        <img :src="'/storage/' + user.user.icon" class="img-fluid img-thumbnail rounded-circle" style="width:50px; height:50px; object-fit:cover;">
                        <div class="d-flex flex-column">
                            <h6>{{user.user.display_name}}</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body" v-for="message in messages" :key="message.user_id">
                    <div class="d-flex flex-column align-items-end text-white"  v-if="message.user_id == myUser.accountName" >
                        <div class="">
                            <div class="text-end rounded-pill bg-secondary">
                                <p class="pe-3 ps-3">{{message.text}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-start text-white" v-else>
                        <div class="d-flex flex-row text-end rounded-pill bg-secondary">
                            <img :src="'/storage/' + message.user.icon" class="img-fluid img-thumbnail rounded-circle" style="width:50px; height:50px; object-fit:cover;">
                            <p class="pe-3 ps-3">{{message.text}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="fixed-bottom m-2">
                <form v-on:submit.prevent="send">
                    <div class="d-flex flex-row">
                        <input class="flex-fill rounded-pill me-1" type="text" placeholder="メッセージを入力" v-model="messageText">
                        <input class="rounded-pill ps-2 pe-2" type="submit" value="送信">
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            myUser: this.$store.state.user,
            messages: {},
            messageText: null,
            fetchEnd: false,
        }
    },
    created(){
        var param = {
            roomId: this.$route.params.roomId
        }
        axios.get('/api/room/fetch',{params: param})
            .then((res) => {
                this.fetchEnd = true;
                this.users = res.data.room.user_list;
                this.messages = res.data.room.message;
            });
    },
    methods: {
        send(){
            var param = {
                roomId: this.$route.params.roomId,
                messageText: this.messageText
            }
            axios.post('/api/message/create', param)
                .then((res) => {
                    this.messages = res.data.messages;
                    this.messageText = null;
                });
        }
    }

}
</script>
