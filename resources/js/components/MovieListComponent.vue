<template>
    <div class="card" style="height:600px;">
        <div class="card-body">
            <h6>再生リスト</h6>
            <div class="video-list"
                v-if="video.thumbnail">
                <img :src="video.thumbnail" class="img-fluid " style="width:260px; height:200px; object-fit:cover;" v-on:click="showVideoPlayerModal">
            </div>
            <!-- <div v-else="video.thumbnail">

            </div> -->
        </div>
        <div class="modal" tabindex="-1" ref="videoPlayerModal">
            <div class="modal-dialog h-75">
                <div class="modal-content h-100 m-auto">
                    <div class="modal-body p-0">
                        <iframe class="w-100" :src="video.url" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        作成中
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
                videoPlayerObj: null,
            }
        },
        mounted() {
            this.videoPlayerObj = new Modal(this.$refs.videoPlayerModal, {keyboard: true});
        },
        methods:{
            fetch() {
                axios.get('/api/videoList/fetch')
                    .then((res) => {
                        var video_ids = [];
                        var video_list = {
                            'id':res.data.video_list.id,
                            'thumbnail': '/storage/' + res.data.video_list.thumbnail,
                            'url': 'https://www.youtube.com/embed/' + res.data.video_list.first_video
                        };
                        this.video = video_list;
                        console.log(video_list.url);
                    })
                    .catch((error) => {
                        console.log(error.response.data.message);
                        console.log('get error');
                    });
            },
            showVideoPlayerModal() {
                var self = this;
                self.videoPlayerObj.show();
            },
        }
    }
</script>
