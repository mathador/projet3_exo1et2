import { createApi, fetchBaseQuery } from '@reduxjs/toolkit/query/react';

const API_BASE_URL = 'http://monolithic-app.test';

export const apiCsrf = createApi({
  reducerPath: 'apiCsrf',
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
    getCsrfCookie: builder.query({
      query: () => '/sanctum/csrf-cookie',
    }),
  }),
});

export const {
  useGetCsrfCookieQuery,
} = apiCsrf;
