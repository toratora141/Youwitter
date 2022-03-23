<template>
    <div class="card" style="height:600px;">
        <div class="card-body">
            <h6>再生リスト</h6>
            <div class="video-list"
                v-if="video.thumbnail">
                <img :src="video.thumbnail" class="img-fluid " style="width:260px; height:200px; object-fit:cover;" v-on:click="showVideoPlayerModal">
            </div>
            <div v-show="!havePlaylist" class="alert alert-dark" role="alert" ref="alertCreatePlaylist">
                プレイリストを作成しましょう！
                <router-link v-bind:to="{name:'movieList.create'}">
                    <button class="btn btn-secondary">マイリストの作成</button>
                </router-link>
            </div>
        </div>
        <div class="modal" tabindex="-1" ref="videoPlayerModal">
            <div class="modal-dialog h-75">
                <div class="modal-content h-100 m-auto">
                    <div class="modal-body p-0">
                        <iframe class="w-100 h-100" :src="'https://www.youtube.com/embed/'+video.url" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div class="modal-body p-0 overflow-auto d-flex flex-column">
                        <div class="video m-1 d-flex" v-for="video in videos_array" :key="video.code" v-on:click="changeVideo(video.code)">
                            <img :src="'/storage/' + video.thumbnail" >
                            <p v-text="video.title" class="fz-1 mt-1"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>import { Modal } from 'bootstrap';

    export default{
        props: [
            'user',
        ],
        data: function() {
            return {
                video: {},
                videos_array: {},
                videoPlayerObj: null,
                havePlaylist: true,
            }
        },
        mounted() {
            this.videoPlayerObj = new Modal(this.$refs.videoPlayerModal, {keyboard: true});
            this.havePlaylistObj = new Modal(this.$refs.alertCreatePlaylist, {keyboard: true});
        },
        methods:{
            fetch() {
                axios.get('/api/videoList/fetch')
                    .then((res) => {
                        var video_ids = [];
                        var video_list = {
                            'id':res.data.video_list.id,
                            'thumbnail': '/storage/' + res.data.video_list.thumbnail,
                            'url': res.data.video_list.first_video
                        };
                        var temp = res.data.videos_array;
                        this.videos_array = temp;
                        console.log(this.videos_array[0].id);
                        // for(var key in this.videos_array){
                        //     console.log(key);
                        // }
                        this.video = video_list;
                        this.havePlaylist = true;;
                    })
                    .catch((error) => {
                        this.havePlaylist = false;
                    });
            },
            showVideoPlayerModal() {
                var self = this;
                self.videoPlayerObj.show();
            },
            changeVideo(code){
                var changeUrl = code +' ?autoplay=1';
                this.video.url = code + '?autoplay=1';
            }
        }
    }
</script>
