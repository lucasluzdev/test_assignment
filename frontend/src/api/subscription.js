import { api } from './axios';

export async function readMany(pageSize, currentPage, sort) {
    try {
        const response = await api.get(`/readMany?pageSize=${pageSize}&currentPage=${currentPage}&sort=${sort}`);
        return response;
    }
    catch (error) {
        console.log('Error while trying to execute ReadMany', error);
    }
}

export async function readOne(search, pageSize, currentPage, sort) {
    try {
        const response = await api.get(`/readOne?search=${search}&pageSize=${pageSize}&currentPage=${currentPage}&sort=${sort}`);
        return response;
    }
    catch (error) {
        console.log('Error while trying to execute ReadOne', error);
    }
}

export async function readOneByID(id) {
    try {
        const response = await api.get(`/readOneByID?id=${id}`);
        return response;
    }
    catch (error) {
        console.log('Error while trying to execute ReadOne', error);
    }
}


export async function create({first_name, last_name, email, status}) {
    try {
        const response = await api.post('/create', {first_name, last_name, email, status});
        return response;
    }
    catch (error) {
        console.log('Error while trying to execute Create', error);
    }
}

export async function update({id, first_name, last_name, email, status}) {
    try {
        const response = await api.put('/update', {id, first_name, last_name, email, status});
        return response;
    }
    catch (error) {
        console.log('Error while trying to execute Update', error);
    }
}

export async function remove(id) {
    try {
        const response = await api.delete(`/delete/${id}`);
        return response;
    }
    catch (error) {
        console.log('Error while trying to execute Delete/Remove', error);
    }
}