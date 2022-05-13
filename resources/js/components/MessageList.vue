<template>
    <div class="d-flex flex-column">
        <div class="card">
            <div class="card-header" v-on:click="fetchFollows">
                <h6>新しくメッセージを送る</h6>
            </div>
            <div class="modal" ref="newMessageModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header text-center">
                            <h5 class="modal-title m-auto">メッセージを送る</h5>
                            <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="mt-5 mb-5 text-center" v-show="!fetchFollowsEnd">
                            <div class="spinner-border text-secondary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div v-if="hasFollows">
                        <p>誰にメッセージを送りますか？</p>
                            <div v-for="follow in follows" :key="follow.account_name">
                                <div style="text-decoration: none; color: rgb(20, 22, 25);"  v-on:click="prepareMessagePage(follow.user)">
                                    <div class="d-flex flex-row align-items-center">
                                        <img :src="'/storage/' + follow.user.icon" class="img-fluid img-thumbnail rounded-circle" style="width:100px; height:100px; object-fit:cover;">
                                        <div class="d-flex flex-column">
                                            <h5>{{follow.user.account_name}}</h5>
                                            <p>{{follow.user.display_name}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-5 mb-5 text-center" v-show="!fetchRoomEnd">
            <div class="spinner-border text-secondary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div v-if="fetchRoomEnd">
            <div v-if="hasRooms">
                <div v-for="room in rooms" :key="room.user_list.user_id">
                    <div v-for="userList in room.user_list" :key="userList.user_id">
                        <div v-if="user.accountName != userList.user_id">
                            <router-link style="text-decoration: none; color: rgb(20, 22, 25);" v-bind:to="{name:'message', params:{roomId: userList.room_id}}">
                                <div class="d-flex flex-row align-items-center">
                                    <img :src="'/storage/' + userList.user.icon" class="img-fluid img-thumbnail rounded-circle" style="width:50px; height:50px; object-fit:cover;">
                                    <div class="d-flex flex-column">
                                        <h5>{{userList.user.account_name}}</h5>
                                        <p>{{userList.user.display_name}}</p>
                                    </div>
                                </div>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else>
                <p>メッセージの履歴がありません</p>
            </div>
        </div>
    </div>
</template>

<script>

import { Modal } from 'bootstrap';
export default {
    data:function() {
        return {
            user: this.$store.state.user,
            follows: {},
            hasFollows: false,
            hasRooms: false,
            newMessageObj: null,
            fetchFollowsEnd: false,
            fetchRoomEnd: false,
        }
    },
    mounted(){
        this.newMessageObj = new Modal(this.$refs.newMessageModal);
    },
    created(){
        axios.get('/api/room/fetch')
            .then((res) => {
                this.fetchRoomEnd = true;
                this.rooms = res.data.rooms;
                if(this.rooms.length > 0){
                    this.hasRooms = true;
                }
            });
    },
    methods: {
        fetchFollows(){
            this.newMessageObj.show();
            if(this.hasFollows){
                return;
            }
            axios.get('/api/user/follow/fetch')
                .then((res) => {
                    this.fetchFollowsEnd = true;
                    this.follows = res.data.follows;
                    if(this.follows != null){
                        this.hasFollows = true;
                    }
                });
        },
        prepareMessagePage(user){
            this.newMessageObj.hide();
            var param = {
                users: [
                    this.$store.state.user.accountName,
                    user.account_name,
                ]
            }

            axios.post('/api/room/create', param)
                .then((res) => {
                    this.$router.push({name:'message', params:{roomId: res.data.room.room_id}});
                });
        }
    }
}
</script>
