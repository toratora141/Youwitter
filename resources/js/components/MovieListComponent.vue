<template>
    <div class="card text-center p-3">
        <h6>再生リスト</h6>
        <div class="text-center" v-show="!fetchEnd">
            <div class="mt-5 mb-5 spinner-border text-secondary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div class="card-body p-1" v-if="havePlaylist">
            <div>
                <p class="m-0 text-end">{{diffTime(videoLists.updated_at)}}</p>
            </div>
            <div class="video-list" v-if="videoLists.thumbnail">
                <img
                    :src="'/storage/' + videoLists.thumbnail"
                    class="img-fluid"
                    style="width: 260px; height: 200px; object-fit: cover"
                    v-on:click="showVideoPlayerModal"
                />
            </div>
        </div>
        <div
            v-if="alert"
            class="alert alert-dark w-75 m-auto"
            role="alert"
            ref="alertCreatePlaylist"
        >
            {{ message }}
            <div v-if="isMyProfile">
                <router-link v-bind:to="{ name: 'movieList.create' }">
                    <button class="btn btn-secondary">マイリストの作成</button>
                </router-link>
            </div>
        </div>
        <div class="modal" tabindex="-1" ref="videoPlayerModal">
            <div class="modal-dialog h-75">
                <div class="modal-content h-100 m-auto">
                    <div class="modal-header p-1">
                        <div class="modal-title" v-if="havePlaylist">
                            <div class="d-flex flex-row align-items-center">
                                <img :src="'/storage/' + videoLists.user.icon" class="img-fluid img-thumbnail rounded-circle" style="width:50px; height:50px; object-fit:cover;">
                                <div class="d-flex flex-column align-items-center">
                                    <p class="ms-2 mb-0">{{videoLists.user.display_name}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body p-0 h-75" v-html="iframe"></div>
                    <div class="card p-1">
                        {{ playVideo.title }}
                        <div v-if="!isMyProfile">
                            <div v-if="!fav">
                                <i
                                    class="bi bi-heart fs-5"
                                    v-on:click="goodedVideo(playVideo.code)"
                                ></i>
                            </div>
                            <div v-else>
                                <i
                                    class="bi bi-heart-fill fs-5"
                                    v-on:click="deleteGoodedVideo(playVideo.code)"
                                ></i>
                            </div>
                        </div>
                    </div>
                    <div
                        class="modal-body p-0 overflow-auto d-flex flex-column h-100"
                    >
                        <div
                            class="video m-1 d-flex"
                            v-for="video in videos"
                            :key="video.code"
                            v-on:click="changeVideo(video)"
                        >
                            <img :src="'/storage/' + video.thumbnail" />
                            <div class="d-flex flex-column flex-fill">
                                <p class="text-end m-0">{{diffTime(video.created_at)}}</p>
                                <p v-text="video.title" class="fz-1 mt-1 m-auto"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { Modal } from "bootstrap";

export default {
    props: ["user"],
    data: function () {
        return {
            videoLists: {},
            videos: {},
            playVideo: {},
            videoPlayerObj: null,
            havePlaylist: false,
            message: null,
            alert: false,
            isMyProfile: this.$parent.isMyProfile,

            iframe: null,
            fav: false,
            goodList: [],
            deleteGoodList: [],
            userId: null,
            fetchEnd: false,
        };
    },
    mounted() {
        this.videoPlayerObj = new Modal(this.$refs.videoPlayerModal, {
            keyboard: true,
        });
    },
    methods: {
        fetch(videoLists, videos, user) {
            this.fetchEnd = true;
            if ((videoLists === null || videoLists === undefined) && !this.$parent.isMyProfile) {
                this.havePlaylist = false;
                this.alert = true;
                this.message = "プレイリストを準備中のようです...";
                return;
            } else if (videoLists === null || videoLists === undefined) {
                this.havePlaylist = false;
                this.alert = true;
                this.message = "プレイリストを作成しましょう!";
                return;
            }
            this.$parent.fetchEnd = true;
            this.havePlaylist = true;
            this.videoLists = videoLists;
            this.videos = videos;
            this.playVideo = videos[0];
            this.playVideo.url = this.videoLists.first_video;
            this.userId = user;
            this.iframe =
                '<iframe class="w-100 h-100" src="https://www.youtube.com/embed/' +
                this.videoLists.first_video +
                '?controls=0&rel=0"title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        },
        showVideoPlayerModal() {
            this.videoPlayerObj.show();
        },
        changeVideo(video) {
            var self = this;
            this.fav = false;
            this.playVideo = video;
            if(video.good !== null || this.goodList.includes(video.code)){
                this.fav = true;
            }
            if(this.deleteGoodList.includes(video.code)){
                this.fav = false;
            }
            self.playVideo.url = video.code + "?autoplay=1";
            this.$set(this.playVideo, "url", video.code);
            this.$nextTick(() => {
                this.iframe =
                    '<iframe class="w-100 h-100" src="https://www.youtube.com/embed/' +
                    this.playVideo.url +
                    '?controls=0&rel=0"title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
            });
        },
        goodedVideo(videoId) {
            axios.post("/api/good/create", {
                videoId: videoId,
                userId: this.userId
                })
                .then((res) => {
                    this.fav = !this.fav;
                });
            this.goodList.push(videoId);
        },
        deleteGoodedVideo(videoId) {
            axios.post("/api/good/destroy", {
                    videoId: videoId,
                    userId: this.userId
                })
                .then((res) => {
                    this.fav = !this.fav;
                });
            var index = this.goodList.indexOf(videoId);
            this.goodList.splice(index, 1);
            this.deleteGoodList.push(videoId);
        },
    },
};
</script>
