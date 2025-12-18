import React from 'react';
import { useGetNotesQuery } from '../services/api';

const Notes = () => {
  const { data, isLoading, isError, error } = useGetNotesQuery();

  let content;

  if (isLoading) {
    content = <p>Chargement des notes...</p>;
  } else if (isError) {
    content = <p>Erreur: {error?.data?.message || 'Une erreur est survenue lors du chargement des notes.'}</p>;
  } else if (data) {
    const notes = Array.isArray(data) ? data : Array.isArray(data.data) ? data.data : [];

    content = (
      <div>
        {notes.map((note) => (
          <div key={note.id} id={note.id} >
            <p>{note.text}</p>
            {note.tag && (
              <div className="flex flex-wrap gap-1 mt-2">
                <span>{note.tag.name}</span>
              </div>
            )}
          </div>
        ))}
      </div>
    );
  } else {
    content = <p>Aucune note à afficher.</p>;
  }

  return (
    <div>
      <h2 className="text-xl font-semibold mb-4">Mes notes</h2>
      {content}
    </div>
  );
};

export default Notes;