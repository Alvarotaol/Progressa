
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
    id?: ModelId;
    label: string;
    color: string;
}

export interface ProjectListItem {
    id: ModelId;
    name: string;
    description?: string;
}

export interface Project {
    id?: ModelId;
    name: string;
    description?: string;
    user_id?: ModelId;
    is_active: boolean;
    is_public: boolean;
    tags?: Tag[];
    public_slug?: string;
    created_at?: string;
    updated_at?: string;
}

export interface Post {
    id: ModelId;
    content: string;
    project_id: ModelId;
    is_hidden?: boolean;
    tags: Tag[];
    created_at: string;
    updated_at: string;
}

export type ModelId = number | string;