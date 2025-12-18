import './App.css';
import { Routes, Route } from 'react-router-dom';
import { useSelector } from 'react-redux';
import Dashboard from './pages/Dashboard';
import LoginPage from './pages/LoginPage';
import PrivateRoute from './components/PrivateRoute';
import LogoutButton from './components/LogoutButton';

function App() {
  const { isAuthenticated } = useSelector((state) => state.auth);

  return (
    <div className="min-h-screen bg-neutral-100 dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 p-4">
      {isAuthenticated && (
        <nav className="flex justify-end p-2">
          <LogoutButton />
        </nav>
      )}
      <Routes>
        <Route path="/login" element={<LoginPage />} />
        <Route
          path="/"
          element={
            <PrivateRoute>
              <Dashboard />
            </PrivateRoute>
          }
        />
      </Routes>
    </div>
  );
}

export default App;
