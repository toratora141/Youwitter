<template>
    <div class="card">
        <div class="card-body">
            <div class="movie-list"
                v-if="movie.id"
                v-text="movie.id">
            </div>
        </div>
    </div>
</template>

<script>
    export default{
        props: [
            'user',
        ],
        data: function() {
            return {
                movie: {},
            }
        },
        methods:{
            fetch() {
                axios.get('/api/movieList/fetch')
                    .then((res) => {
                        var movie_ids = [];
                        var movie_list = {'id':res.data.movie_list.id};
                        this.movie = movie_list;
                        console.log(this.movie.id);
                            // console.log(movie_list);
                        // console.log(this.movie);
                        // for(var key in movie_list) {
                        // }
                    })
                    .catch((error) => {
                        console.log(error.response.data.message);
                    })
            }
        }
    }
</script>
