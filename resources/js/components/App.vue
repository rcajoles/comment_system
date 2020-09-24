<template>
    <div class="layout">

        <div class="form">
            <div class="card">
                <h5 class="card-header">Comments</h5>
                <div class="card-body">
                    <b-form @submit="onSubmit" @reset="onReset">

                        <b-form-group id="input-name" label="Your Name:" label-for="name">
                            <b-form-input
                            id="name"
                            v-model="form.name"
                            required
                            placeholder="Enter name"
                            ></b-form-input>
                        </b-form-group>

                        <b-form-group id="input-comment" label="Comment:" label-for="comment">
                            <textarea id="comment" v-model="form.comment" class="form-control" rows="3" required></textarea>
                        </b-form-group>

                        <b-button type="submit" variant="primary">Submit</b-button>
                        <b-button type="reset" variant="danger">Reset</b-button>
                    </b-form>
                </div>
            </div>
        </div>

        <div class="comments">

            <div v-if="posts.length">
                <template v-for="post in posts" >
                    <b-card
                        class="post-card-margin"
                        :title="post.name"
                        :sub-title="post.created_at"
                        :key="post.id"
                        v-if="post.parent === null && post.level == 1"
                    >

                        <b-card-body>

                            <b-card-text>
                                {{ post.comment }}
                            </b-card-text>

                                <b-form @submit="onSubmit2($event, post.id)">

                                    <b-button type="submit" variant="link">Reply</b-button>

                                    <b-form-group id="input-name" :label-for="'level_2_name_' + post.id">
                                        <b-form-input
                                        :id="'level_2_name_' + post.id"
                                        v-model="form2.names[post.id]"
                                        type="text"
                                        required
                                        placeholder="Enter name"
                                        ></b-form-input>
                                    </b-form-group>

                                    <b-form-group id="input-comment" :label-for="'level_2_comment_' + post.id">
                                        <b-form-input
                                        :id="'level_2_comment_' + post.id"
                                        v-model="form2.comments[post.id]"
                                        type="text"
                                        required
                                        class="form-control"
                                        placeholder="Enter comment"
                                        ></b-form-input>
                                    </b-form-group>


                                </b-form>

                            <div v-if="commentLevelTwo.length">
                                <template v-for="comment2 in commentLevelTwo">
                                    <b-card
                                        class="comment-level-two"
                                        :title="comment2.name"
                                        :sub-title="comment2.created_at"
                                        v-if="comment2.parent == post.id && comment2.level == 2 "
                                        :key="comment2.id"
                                    >

                                        <b-card-text>
                                            {{comment2.comment}}
                                        </b-card-text>

                                        <a href="#" class="card-link">Reply</a>

                                        <!-- <b-card title="Card title" sub-title="Card subtitle">
                                            <b-card-text>
                                            Some quick example text to build on the <em>card title</em> and make up the bulk of the card's
                                            content.
                                            </b-card-text>


                                        </b-card> -->

                                    </b-card>
                                </template>
                            </div>

                        </b-card-body>

                    </b-card>
                </template>
            </div>

        </div>
    </div>
</template>

<script>
import Comment from "../services/comment";
 export default {
     beforeMount() {
       this.initialize();
    },
     data() {
         return {
             form: {
                 name: '',
                 comment: '',
                 leve: 1,
                 parent: null,
             },
             form2: {
                 names: [],
                 comments: [],
                 level: 2,
                 parents: []
             },
             show: true,
             posts: [],
             commentLevelTwo: [],
         }
     },
     methods: {
         initialize() {
             Comment.getAll()
                .then(response => {

                    console.log('response: ', response);

                    if (response.status === 200) {

                        response.data.data.map(comment => {
                            if (comment.parent == null) {
                                this.posts.push(comment);
                            } else if (comment.parent != null && comment.level == 2) {
                                this.commentLevelTwo.push(comment);
                            }
                        })

                        console.log('this.posts: ', this.posts);
                        console.log('this.commentLevelTwo: ', this.commentLevelTwo);
                    }
                })
                .catch(error => {
                    console.log('error: ', error);
                })

         },
         onSubmit(evt) {
             evt.preventDefault()

             Comment.postComment(this.form)
                    .then(result => {
                        console.log('result: ', result);
                        if (result.status == 200) {
                            this.initialize();
                        }
                    })
                    .catch(error => {
                        console.log('error: ', error);
                    })
         },
         onSubmit2(evt, val) {
             evt.preventDefault()

            let newForm = {
                name: this.form2.names[val],
                comment: this.form2.comments[val],
                level: 2,
                parent: val,
            }

             console.log('newFOrm: ', newForm);

             Comment.postComment(newForm)
                    .then(result => {
                        console.log('newForm result: ', result);
                        if (result.status == 200) {
                            this.initialize();

                            this.form2 = {
                                names: [],
                                comments: [],
                                level: 2,
                                parents: []
                            };
                        }
                    })
                    .catch(error => {
                        console.log('error: ', error);
                    })
         },
         onReset(evt) {
             evt.preventDefault()
             // Reset our form values
             this.form.name = ''
             this.form.comment = ''
             // Trick to reset/clear native browser form validation state
             this.show = false
             this.$nextTick(() => {
                 this.show = true
             })
         },
     }
 }
</script>

<style lang="scss" scoped>
.layout {
    position: unset;
    margin: 50px;
}

.post-card-margin {
    margin-top: 20px;
    margin-bottom: 40px;
}

.comment-level-two {
    margin: 10px;
}
</style>
