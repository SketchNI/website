export const postType = {
    id: Number,
    slug: String,
    title: String,
    content: String,
    excerpt: String,
    categories: Array,
    published_at: String,
    created_at: String,
    updated_at: String,
    author: {
        id: Number,
        name: String,
        email: String,
    }
}
