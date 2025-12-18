import React, { useState } from 'react';
import { useAddTagMutation } from '../services/api';

const TagForm = () => {
  const [tagName, setTagName] = useState('');
  const [status, setStatus] = useState('idle'); // 'idle' | 'loading' | 'succeeded' | 'failed'
  const [error, setError] = useState(null);
  const [addTag, { isLoading }] = useAddTagMutation();

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!tagName.trim()) {
      setError('Le nom du tag ne peut pas être vide.');
      return;
    }

    try {
      setStatus('loading');
      setError(null);
      await addTag(tagName).unwrap();
      setTagName('');
      setStatus('succeeded');

      // After success, you might want to refetch tags to update the list
      // dispatch(fetchTags()); // Uncomment when addTag and fetchTags are fully implemented
    } catch (err) {
      setError(err.message || 'La création du tag a échoué.');
      setStatus('failed');
    }
  };

  return (
    <div>
      <h2 className="text-xl font-semibold mb-4">Créer un nouveau tag</h2>
      <form onSubmit={handleSubmit} className="flex flex-col gap-4">
        <div>
          <label htmlFor="tagName" className="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
            Nom du tag
          </label>
          <input
            type="text"
            id="tagName"
            className="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-100"
            value={tagName}
            onChange={(e) => setTagName(e.target.value)}
            disabled={status === 'loading' || isLoading}
          />
        </div>
        <button
          type="submit"
          className="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
          disabled={status === 'loading' || isLoading}
        >
          {status === 'loading' || isLoading ? 'Création...' : 'Créer le tag'}
        </button>
        {error && <p className="text-red-500 text-sm">{error}</p>}
        {status === 'succeeded' && !error && <p className="text-green-500 text-sm">Tag créé avec succès!</p>}
      </form>
    </div>
  );
};

export default TagForm;