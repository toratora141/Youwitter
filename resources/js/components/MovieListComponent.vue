<template>
    <div class="card" style="height:600px;">
        <h6>再生リスト</h6>
        <div class="card-body" v-if="havePlaylist">
            <div class="video-list"
                v-if="videoLists.thumbnail">
                <img :src="videoLists.thumbnail" class="img-fluid " style="width:260px; height:200px; object-fit:cover;" v-on:click="showVideoPlayerModal">
            </div>
        </div>
        <div v-show="!havePlaylist" class="alert alert-dark" role="alert" ref="alertCreatePlaylist">
            {{message}}
            <router-link v-bind:to="{name:'movieList.create'}">
                <button class="btn btn-secondary">マイリストの作成</button>
            </router-link>
        </div>
        <div class="modal" tabindex="-1" ref="videoPlayerModal">
            <div class="modal-dialog h-75">
                <div class="modal-content h-100 m-auto">
                    <div class="modal-body p-0">
                        <iframe class="w-100 h-100" :src="'https://www.youtube.com/embed/' + playVideo.url" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div class="modal-body p-0 overflow-auto d-flex flex-column">
                        <div class="video m-1 d-flex" v-for="video in videos" :key="video.code" v-on:click="changeVideo(video.code)">
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
                videoLists: this.$parent.videoLists,
                videos: this.$parent.videos,
                playVideo: {},
                videoPlayerObj: null,
                havePlaylist: null,
                message: null,
            }
        },
        mounted() {
            this.videoPlayerObj = new Modal(this.$refs.videoPlayerModal, {keyboard: true});
            this.havePlaylistObj = new Modal(this.$refs.alertCreatePlaylist, {keyboard: true});
        },
        methods:{
            fetch(videoLists, user) {
                console.log(this.videoLists);
                if(this.videoLists === null && !this.$parent.myProfile){
                    console.log('otherUser page and no playlist');
                    this.havePlaylist = false;
                    this.message = 'プレイリストを準備中のようです...';
                    return;
                }else if(videoLists === undefined){
                    this.havePlaylist = false;
                    return;
                }
                // console.log(this.havePlaylist);
                // this.videoLists = {
                //     'id':videoLists.id,
                //     'thumbnail': '/storage/' + videoLists.thumbnail,
                //     'url': videoLists.first_video
                // }
                // this.playVideo = {
                //     url: videoLists.first_video
                // };
                // axios.get('/api/videoList/fetch',{
                //     params:{
                //         id: videoLists.id
                //     }
                // })
                //     .then((res) => {
                //         this.videos = res.data.videos;
                //         this.havePlaylist = true;
                //         if(videoLists === undefined) this.havePlaylist =false;
                //     })
                //     .catch((error) => {
                //         this.havePlaylist = false;
                //         this.message = 'プレイリストを作成しましょう！'
                //         console.log(error);
                //     });
            },
            showVideoPlayerModal() {
                this.videoPlayerObj.show();
            },
            changeVideo(code){
                this.playVideo.url = code + '?autoplay=1';
            }
        }
    }
</script>
