<template>
    <!-- <div class="card" style="height:600px;"> -->
    <div class="card">
        <h6>再生リスト</h6>
        <div class="card-body" v-if="havePlaylist">
            <div class="video-list"
                v-if="videoLists.thumbnail">
                <img :src="'/storage/' + videoLists.thumbnail" class="img-fluid " style="width:260px; height:200px; object-fit:cover;" v-on:click="showVideoPlayerModal">
            </div>
        </div>
        <div v-if="alert" class="alert alert-dark" role="alert" ref="alertCreatePlaylist">
            {{message}}
            <div v-if="isMyProfile">
                <router-link v-bind:to="{name:'movieList.create'}">
                    <button class="btn btn-secondary">マイリストの作成</button>
                </router-link>
            </div>
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
                videoLists: {},
                videos: {},
                playVideo: {},
                videoPlayerObj: null,
                havePlaylist: false,
                message: null,
                alert: false,
                isMyProfile: this.$parent.isMyProfile
            }
        },
        mounted() {
            this.videoPlayerObj = new Modal(this.$refs.videoPlayerModal, {keyboard: true});
        },
        methods:{
            fetch(videoLists, videos) {
                console.log(videoLists);
                if(videoLists === null && !this.$parent.isMyProfile){
                    this.havePlaylist = false;
                    this.alert = true;
                    this.message = 'プレイリストを準備中のようです...';
                    return;
                }else if(videoLists === null){
                    this.havePlaylist = false;
                    this.alert = true;
                    this.message = 'プレイリストを作成しましょう!';
                    return;
                }
                this.havePlaylist = true;
                this.videoLists = videoLists;
                this.videos = videos;
                this.playVideo.url = this.videoLists.first_video;
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
