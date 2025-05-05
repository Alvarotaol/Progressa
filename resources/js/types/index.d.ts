
export interface Auth {
    user: User;
}


export type HttpMethod = "get" | "post" | "put" | "delete";

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    created_at: string;
    updated_at: string;
}

export interface Tag {
    label: string;
    color: string;
}

export interface Project {
    id: ModelId;
    name: string;
    description?: string;
}

export interface Post {
    id: ModelId;
    content: string;
    project_id: ModelId;
    tags: Tag[];
    created_at: string;
    updated_at: string;
}

export type ModelId = number | string;