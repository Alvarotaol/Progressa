import axios from "axios";
import storage from "./storage";
//import utils from "./utils";

import { ModelId, HttpMethod } from "@/types";
const baseURL = "/api/";
const apiRequester = axios.create({ baseURL, withCredentials: true });

const getHeaders = function (auth: boolean) {
	const headers = {Authorization: ""};
	if (auth) {
		const token = storage.getToken();
		headers["Authorization"] = token || "";
		if (!token) {
			return null;
		}
	}
	return headers;
};

function getUrl(api: string, url_params: any = {}) {
	let url = urlList[api]["url"];
	for (const param in url_params) {
		url = url.replace(`:${param}`, url_params[param]);
	}
	return url;
}

/* eslint-disable */
const request = (api: string, data: any = {}, url_params: any = {}) => {
	const headers = getHeaders(urlList[api]["auth"]);

	if (!headers) {
		return Promise.reject("Não logado");
	}

	let config: any = { headers };
	switch (urlList[api]["method"]) {
		case "get":
			config["params"] = data;
			return apiRequester.get(getUrl(api, url_params), config).catch((err) => {
				if (err.response.status == 401) {
					//storage.clear();
					window.location.replace("/");
				}
				return Promise.reject(err);
			});
		case "put":
			return apiRequester.put(getUrl(api, url_params), data, config);
		case "delete":
			return apiRequester.delete(getUrl(api, url_params), config);
		case "post":
		default:
			return apiRequester.post(getUrl(api, url_params), data, config);
	}
};

class Model {
	route: string;
	config: any;
	constructor(route: string) {
		let slash = "";
		if (route[route.length - 1] != "/") {
			slash = "/";
		}
		this.route = route + slash;
		this.config = {
			headers: getHeaders(true), //Auth sempre true
		};
	}

	list(params: any = null) {
		let config = {
			...this.config,
			params,
		};
		return apiRequester.get(this.route, config);
	}

	get(model_id: ModelId, params = null) {
		if (params) {
			return apiRequester.get(`${this.route}${model_id}/`, {
				...this.config,
				params,
			});
		}
		return apiRequester.get(`${this.route}${model_id}/`, this.config);
	}

	update(model: any, id:ModelId|null = null) {
		let model_id = id ? id : model.id;
		if (model instanceof FormData) {
			model.append("_method", "PUT");
			return apiRequester.post(`${this.route}${model_id}/`, model, this.config);
		}
		return apiRequester.put(`${this.route}${model_id}/`, model, this.config);
	}

	create(model: any) {
		return apiRequester.post(this.route, model, this.config);
	}

	delete(model_id: ModelId) {
		return apiRequester.delete(`${this.route}${model_id}/`, this.config);
	}

	//Chama uma url específica de um Model
	call(url: string, data = {}, method: HttpMethod = "get") {
		return apiRequester[method](`${this.route}${url}`, data, this.config);
	}

}

const urlList: any = {
	"logout": {
		url: "/logout",
		method: "post",
		auth: true
	}
 };

export {
	request,
	Model,
};
