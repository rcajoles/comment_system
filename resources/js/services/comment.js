import axios from "axios";


export default {

    getAll() {
        return axios({
            method: 'get',
			url: '/api/comment',
        });
    },

    postComment(data) {
        return axios({
            method: 'post',
            url: '/api/comment',
            data: data
        });
    }
}
