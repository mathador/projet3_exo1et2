import { createApi, fetchBaseQuery } from '@reduxjs/toolkit/query/react';

const API_BASE_URL = 'http://monolithic-app.test/api';

export const api = createApi({
  reducerPath: 'api',
  tagTypes: ['Tag'],
  baseQuery: fetchBaseQuery({
    baseUrl: API_BASE_URL,
    prepareHeaders: (headers) => {
      const token = localStorage.getItem('authToken');
      if (token) {
        headers.set('authorization', `Bearer ${token}`);
      }
      headers.set('Accept', 'application/json');
      return headers;
    },
  }),
  endpoints: (builder) => ({
    getTags: builder.query({
      query: () => '/tags',
      providesTags: (result) =>
        result && Array.isArray(result.data)
          ? [
              ...result.data.map((tag) => ({ type: 'Tag', id: tag.id })),
              { type: 'Tag', id: 'LIST' },
            ]
          : [{ type: 'Tag', id: 'LIST' }],
    }),
    getTagById: builder.query({
      query: (id) => '/tags/' + id,
    }),
    addTag: builder.mutation({
      query: (tagName) => ({
        url: '/tags',
        method: 'POST',
        body: {
          name: tagName,
        },
      }),
      invalidatesTags: [{ type: 'Tag', id: 'LIST' }],
    }),
    removeTag: builder.mutation({
      query: (id) => ({
        url: `/tags/${id}`,
        method: 'DELETE',
      }),
      invalidatesTags: (result, error, id) => [
        { type: 'Tag', id },
        { type: 'Tag', id: 'LIST' },
      ],
    }),
    getNotes: builder.query({
      query: () => '/notes',
    }),
    login: builder.mutation({
      query: (credentials) => ({
        url: '/auth/login',
        method: 'POST',
        body: credentials,
      }),
    }),
    logout: builder.mutation({
      query: () => ({
        url: '/auth/logout',
        method: 'POST',
      }),
    }),
    register: builder.mutation({
      query: (body) => ({
        url: '/auth/register',
        method: 'POST',
        body,
      }),
    }),
  })
});

export const {
  useLoginMutation,
  useLogoutMutation,
  useGetNotesQuery,
  useGetTagsQuery,
  useAddTagMutation,
  useRemoveTagMutation,
} = api;