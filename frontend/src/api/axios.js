import axios from 'axios';

const api = axios.create({
    baseURL: 'http://localhost:5172',
    timeout: 30000,
    headers: {'Content-type': 'application/json; charset=utf-8'}
})

export { api };