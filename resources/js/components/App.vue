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
                                        class="comment-level"
                                        :title="comment2.name"
                                        :sub-title="comment2.created_at"
                                        v-if="comment2.parent == post.id && comment2.level == 2 "
                                        :key="comment2.id"
                                    >

                                        <b-card-text>
                                            {{comment2.comment}}
                                        </b-card-text>

                                        <b-form @submit="onSubmit3($event, comment2.id)">

                                            <b-button type="submit" variant="link">Reply</b-button>

                                            <b-form-group id="input-name" :label-for="'level_3_name_' + comment2.id">
                                                <b-form-input
                                                :id="'level_3_name_' + comment2.id"
                                                v-model="form3.names[comment2.id]"
                                                type="text"
                                                required
                                                placeholder="Enter name"
                                                ></b-form-input>
                                            </b-form-group>

                                            <b-form-group id="input-comment" :label-for="'level_3_comment_' + comment2.id">
                                                <b-form-input
                                                :id="'level_3_comment_' + comment2.id"
                                                v-model="form3.comments[comment2.id]"
                                                type="text"
                                                required
                                                class="form-control"
                                                placeholder="Enter comment"
                                                ></b-form-input>
                                            </b-form-group>


                                        </b-form>

                                        <div v-if="commentLevelThree.length">
                                            <template v-for="comment3 in commentLevelThree">
                                                <b-card
                                                    class="comment-level"
                                                    :title="comment3.name"
                                                    :sub-title="comment3.created_at"
                                                    v-if="comment3.parent == comment2.id && comment3.level == 3 "
                                                    :key="comment3.id"
                                                >
                                                    <b-card-text>
                                                        {{ comment3.comment }}
                                                    </b-card-text>


                                                </b-card>
                                            </template>
                                        </div>

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
import CommentService from "../services/comment";

 export default {
     beforeMount() {
       this.initialize();
    },
     data() {
         return {
             form: {
                 name: '',
                 comment: '',
                 level: 1,
                 parent: null,
             },
             form2: {
                 names: [],
                 comments: [],
                 level: 2,
                 parents: []
             },
             form3: {
                 names: [],
                 comments: [],
                 level: 3,
                 parents: []
             },
             show: true,
             posts: [],
             commentLevelTwo: [],
             commentLevelThree: [],
         }
     },
     methods: {
         /**
          * Populate Comment UI with data
          * @param void
          */
         initialize() {

             CommentService.getAll()
                .then(response => {

                    if (response.status === 200) {
                        this.posts = [],
                        this.commentLevelTwo = [],
                        this.commentLevelThree = [],

                        response.data.data.map(comment => {
                            if (comment.parent == null) {
                                this.posts.push(comment);
                            } else if (comment.parent != null && comment.level == 2) {
                                this.commentLevelTwo.push(comment);
                            } else if (comment.parent != null && comment.level == 3) {
                                this.commentLevelThree.push(comment);
                            }
                        })

                    }
                })
                .catch(error => {
                    console.log('error: ', error);
                })

         },
         /**
          * Submit for Main Comment(Post)
          * @param evt
          */
         onSubmit(evt) {
             evt.preventDefault()

             CommentService.postComment(this.form)
                    .then(result => {

                        if (result.data.status == 200) {
                            this.initialize();

                                this.form = {
                                    name: '',
                                    comment: '',
                                    level: 1,
                                    parent: null,
                                };

                        } else if (result.data.status == 400) {
                            if (result.data.errors) {
                                this.showToast('danger', result.data.errors);
                                console.log('error!', result);
                            }
                        }
                    })
                    .catch(error => {
                        console.log('error: ', error);
                    })
         },
         /**
          * Submit for Comment Level 2
          * @param evt
          * @param val
          */
         onSubmit2(evt, val) {
             evt.preventDefault()

            let newForm = {
                name: this.form2.names[val],
                comment: this.form2.comments[val],
                level: 2,
                parent: val,
            }

             CommentService.postComment(newForm)
                    .then(result => {

                        console.log('result: ', result);

                        if (result.data.status == 200) {
                            this.initialize();

                            this.form2 = {
                                names: [],
                                comments: [],
                                parents: []
                            };

                        } else if (result.data.status == 400) {
                            if (result.data.errors) {
                                this.showToast('danger', result.data.errors);
                                console.log('error!', result);
                            }
                        }

                    })
                    .catch(error => {
                        console.log('error: ', error);
                    })
         },
         /**
          * Submit for Comment Level 3
          * @param evt
          * @param val
          */
         onSubmit3(evt, val) {
             evt.preventDefault()

            let newForm = {
                name: this.form3.names[val],
                comment: this.form3.comments[val],
                level: 3,
                parent: val,
            }

             CommentService.postComment(newForm)
                    .then(result => {

                        if (result.data.status == 200) {
                            this.initialize();

                            this.form3 = {
                                names: [],
                                comments: [],
                                parents: []
                            };
                        } else if (result.data.status == 400) {
                            if (result.data.errors) {
                                this.showToast('danger', result.data.errors);
                                console.log('error!', result);
                            }
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
         showToast(variant = null, msg) {

             let title = ''
             if (variant == 'danger') {
                 title = 'Error'
             }

            this.$bvToast.toast(msg[0], {
                title: `${title || 'default'}`,
                variant: variant,
                solid: true
            })
        }
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

.comment-level {
    margin: 10px;
}
</style>
