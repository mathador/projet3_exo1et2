import { createSlice } from '@reduxjs/toolkit';

const initialState = {
  user: localStorage.getItem('authToken') ? { email: 'authenticated' } : null, // Simple check if token exists
  isAuthenticated: !!localStorage.getItem('authToken'),
  status: 'idle', // 'idle' | 'loading' | 'succeeded' | 'failed'
  error: null,
};

const authSlice = createSlice({
  name: 'auth',
  initialState,
  reducers: {
    setUser: (state, action) => {
      state.user = action.payload;
      state.isAuthenticated = !!action.payload;
      if (action.payload) {
        state.status = 'succeeded';
      }
    },
    clearAuth: (state) => {
      state.user = null;
      state.isAuthenticated = false;
      state.status = 'idle';
      state.error = null;
      localStorage.removeItem('authToken');
    },
    setAuthError: (state, action) => {
      state.error = action.payload;
      state.status = 'failed';
    }
  },
});

export const { setUser, clearAuth, setAuthError } = authSlice.actions;

export default authSlice.reducer;