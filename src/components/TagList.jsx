import React from 'react';
import { useGetTagsQuery } from '../services/api';

const TagList = () => {
  const { data, isLoading, isError, error } = useGetTagsQuery();

  let content;

  if (isLoading) {
    content = <p>Chargement des tags...</p>;
  } else if (isError) {
    content = <p>Erreur: {error?.data?.message || 'Une erreur est survenue lors du chargement des tags.'}</p>;
  } else if (data) {
    const tags = Array.isArray(data) ? data : Array.isArray(data.data) ? data.data : [];
    content = (
      <div className="flex flex-wrap gap-2">
        <ul>
          {tags.map((tag) => (
            <li key={tag.id} id={tag.id}>
              {tag.name}
            </li>
          ))}
        </ul>
      </div>
    );
  } else {
    content = <p>Aucun tag à afficher.</p>;
  }

  return (
    <div>
      <h2 className="text-xl font-semibold mb-4">Tags disponibles</h2>
      {content}
    </div>
  );
};

export default TagList;
