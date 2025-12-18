import { useDispatch } from 'react-redux';
import { useNavigate } from 'react-router-dom';
import { useLogoutMutation, api } from '../services/api';
import { clearAuth } from '../features/auth/authSlice';

function LogoutButton() {
  const dispatch = useDispatch();
  const navigate = useNavigate();
  const [logout] = useLogoutMutation();

  const handleLogout = async () => {
    await logout();
    dispatch(clearAuth());
    dispatch(api.util.resetApiState());
    navigate('/login');
  };

  return (
    <button
      onClick={handleLogout}
      className="py-1 px-3 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm"
    >
      Déconnexion
    </button>
  );
}

export default LogoutButton;
