import {HOST} from '../config/index.js';

export class Service {
	constructor(endpoint) {
		this.url = `${HOST}/${endpoint}`;
	}

	
	doReq(method,data, headers) {
		console.log(this.url);
		return fetch(this.url, {
			method: method,
			mode:"cors",
			headers: headers,
			body:data
		});
	}
}

